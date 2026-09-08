async function renderFinance() {
  const view = document.querySelector('[data-tab="finance"]');
  view.innerHTML = `<div class="muted">Loading...</div>`;
  const res = await api('finance_list', {}, 'POST');
  const hangouts = (res.data || []).sort((a, b) => new Date(b.date) - new Date(a.date));

  addFab(() => openHangoutModal());

  // Totals per person
  let meSpent = 0, bfSpent = 0, meCount = 0, bfCount = 0;
  hangouts.forEach(h => {
    (h.purchases || []).forEach(p => {
      if (p.paidBy === 'me') { meSpent += Number(p.amount); meCount++; }
      else                    { bfSpent += Number(p.amount); bfCount++; }
    });
  });
  const total = meSpent + bfSpent;

  const balHtml = `
    <div class="spend-columns">
      <div class="spend-col spend-me">
        <div class="spend-emoji">${App.users.me.emoji}</div>
        <div class="spend-name">${App.users.me.name}</div>
        <div class="spend-amount">${money(meSpent)}</div>
        <div class="spend-count">${meCount} ${meCount === 1 ? 'purchase' : 'purchases'}</div>
      </div>
      <div class="spend-col spend-bf">
        <div class="spend-emoji">${App.users.bf.emoji}</div>
        <div class="spend-name">${App.users.bf.name}</div>
        <div class="spend-amount">${money(bfSpent)}</div>
        <div class="spend-count">${bfCount} ${bfCount === 1 ? 'purchase' : 'purchases'}</div>
      </div>
    </div>
    ${total > 0 ? `<div class="spend-total muted">Total spent together: <strong>${money(total)}</strong></div>` : ''}
  `;

  if (!hangouts.length) {
    view.innerHTML = balHtml + `
      <div class="empty">
        <div class="empty-icon">💰</div>
        <div>No hangouts yet</div>
        <div class="muted">Tap + to log a hangout</div>
      </div>
    `;
    return;
  }

  view.innerHTML = balHtml + hangouts.map(hangoutCard).join('');

  view.querySelectorAll('.add-purchase-btn').forEach(b => {
    b.addEventListener('click', () => openPurchaseModal(b.dataset.id));
  });
  view.querySelectorAll('.del-purchase-btn').forEach(b => {
    b.addEventListener('click', () => deletePurchase(b.dataset.hangout, b.dataset.pid));
  });
  view.querySelectorAll('.del-hangout-btn').forEach(b => {
    b.addEventListener('click', () => deleteHangout(b.dataset.id));
  });
  view.querySelectorAll('.receipt-btn').forEach(b => {
    b.addEventListener('click', (e) => { e.stopPropagation(); viewReceipt(b.dataset.url); });
  });
}

function hangoutCard(h) {
  const purchases = h.purchases || [];
  const total = purchases.reduce((s, p) => s + Number(p.amount), 0);
  const meTotal = purchases.filter(p => p.paidBy === 'me').reduce((s, p) => s + Number(p.amount), 0);
  const bfTotal = total - meTotal;
  const half = total / 2;
  const diff = meTotal - half;

  let summary = 'Even ✨';
  const meLbl = App.users.me.emoji + ' ' + App.users.me.name;
  const bfLbl = App.users.bf.emoji + ' ' + App.users.bf.name;
  if (diff > 0.01) summary = `${bfLbl} owes ${meLbl} ${money(diff)}`;
  else if (diff < -0.01) summary = `${meLbl} owes ${bfLbl} ${money(Math.abs(diff))}`;

  return `
    <div class="hangout-card">
      <div class="hangout-head">
        <div>
          <div class="hangout-title">${esc(h.title)}</div>
          <div class="hangout-date">${fmtDate(h.date)}${h.location ? ' · 📍 ' + esc(h.location) : ''}</div>
        </div>
        <div style="text-align:right">
          <div class="hangout-total">${money(total)}</div>
          <button class="del-hangout-btn btn-icon" data-id="${h.id}" title="Delete" style="margin-top:4px">${ICON.trash}</button>
        </div>
      </div>
      ${purchases.map(p => `
        <div class="purchase-row">
          <div style="flex:1;min-width:0">
            <span class="chip paid-${p.paidBy}">${App.users[p.paidBy]?.emoji || ''}</span>
            ${esc(p.item)}
            ${p.receipt ? `<button class="receipt-btn" data-url="${esc(p.receipt)}" title="View receipt">🧾</button>` : ''}
          </div>
          <div style="display:flex;gap:8px;align-items:center">
            <span>${money(p.amount)}</span>
            <button class="del-purchase-btn" data-hangout="${h.id}" data-pid="${p.id}" style="background:transparent;border:none;color:var(--danger);cursor:pointer">×</button>
          </div>
        </div>
      `).join('')}
      <div class="hangout-summary">
        ${App.users.me.emoji} paid ${money(meTotal)} · ${App.users.bf.emoji} paid ${money(bfTotal)} → ${summary}
      </div>
      <button class="add-purchase-btn btn btn-ghost btn-sm btn-block" data-id="${h.id}" style="margin-top:10px">
        + Add purchase
      </button>
    </div>
  `;
}

function openHangoutModal() {
  const today = new Date().toISOString().slice(0, 10);
  openModal(`
    <h2>New hangout</h2>
    <div class="form-group">
      <label>Title</label>
      <input class="input" id="ho-title" placeholder="e.g. Movie night" />
    </div>
    <div class="form-group">
      <label>Date</label>
      <input type="date" class="input" id="ho-date" value="${today}" />
    </div>
    <div class="form-group">
      <label>Location (optional)</label>
      <input class="input" id="ho-loc" placeholder="e.g. Pavilion KL" />
    </div>
    <div class="row">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn" onclick="saveHangout()">Create</button>
    </div>
  `);
}

async function saveHangout() {
  const title = document.getElementById('ho-title').value.trim();
  const date = document.getElementById('ho-date').value;
  if (!title || !date) { toast('Title and date required'); return; }
  const location = document.getElementById('ho-loc').value.trim();
  await api('finance_add_hangout', { title, date, location });
  closeModal();
  renderFinance();
}

let pendingReceipt = null;

function openPurchaseModal(hangoutId) {
  pendingReceipt = null;
  openModal(`
    <h2>Add purchase</h2>
    <div class="form-group">
      <label>Item</label>
      <input class="input" id="pu-item" placeholder="e.g. Popcorn combo" />
    </div>
    <div class="form-group">
      <label>Amount (RM)</label>
      <input type="number" step="0.01" class="input" id="pu-amount" placeholder="0.00" />
    </div>
    <div class="form-group">
      <label>Paid by</label>
      <select class="select" id="pu-paid">
        <option value="me" ${App.user === 'me' ? 'selected' : ''}>${App.users.me.emoji} ${App.users.me.name}</option>
        <option value="bf" ${App.user === 'bf' ? 'selected' : ''}>${App.users.bf.emoji} ${App.users.bf.name}</option>
      </select>
    </div>
    <div class="form-group">
      <label>Receipt (optional)</label>
      <div class="pick-row">
        <button type="button" class="pick-btn" id="rc-cam">
          <div style="font-size:24px">📷</div>
          <div>Snap</div>
        </button>
        <button type="button" class="pick-btn" id="rc-gal">
          <div style="font-size:24px">🖼️</div>
          <div>Gallery</div>
        </button>
      </div>
      <input type="file" id="rc-cam-input" accept="image/*" capture="environment" style="display:none" />
      <input type="file" id="rc-gal-input" accept="image/*" style="display:none" />
      <div id="rc-preview" class="feed-preview"></div>
    </div>
    <div class="row">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn" id="pu-save-btn" onclick="savePurchase('${hangoutId}')">Add</button>
    </div>
  `);
  document.getElementById('rc-cam').addEventListener('click', () => document.getElementById('rc-cam-input').click());
  document.getElementById('rc-gal').addEventListener('click', () => document.getElementById('rc-gal-input').click());
  document.getElementById('rc-cam-input').addEventListener('change', (e) => setReceipt(e.target.files[0]));
  document.getElementById('rc-gal-input').addEventListener('change', (e) => setReceipt(e.target.files[0]));
}

function setReceipt(file) {
  if (!file) return;
  pendingReceipt = file;
  const preview = document.getElementById('rc-preview');
  preview.innerHTML = '';
  const wrap = document.createElement('div');
  wrap.className = 'preview-item';
  const img = document.createElement('img');
  img.src = URL.createObjectURL(file);
  const rm = document.createElement('button');
  rm.className = 'preview-remove';
  rm.textContent = '×';
  rm.onclick = () => { pendingReceipt = null; preview.innerHTML = ''; };
  wrap.appendChild(img); wrap.appendChild(rm);
  preview.appendChild(wrap);
}

async function savePurchase(hangoutId) {
  const item = document.getElementById('pu-item').value.trim();
  const amount = parseFloat(document.getElementById('pu-amount').value);
  const paidBy = document.getElementById('pu-paid').value;
  if (!item || !amount || amount <= 0) { toast('Item and amount required'); return; }
  const btn = document.getElementById('pu-save-btn');
  btn.disabled = true; btn.textContent = 'Saving...';

  const fd = new FormData();
  fd.append('hangoutId', hangoutId);
  fd.append('item', item);
  fd.append('amount', amount);
  fd.append('paidBy', paidBy);
  if (pendingReceipt) fd.append('receipt', pendingReceipt);

  await apiUpload('finance_add_purchase', fd);
  pendingReceipt = null;
  closeModal();
  renderFinance();
}

function viewReceipt(url) {
  openModal(`
    <h2>Receipt</h2>
    <div style="text-align:center;margin:12px 0">
      <img src="${esc(url)}" style="max-width:100%;max-height:70vh;border-radius:10px" />
    </div>
    <button class="btn btn-block" onclick="closeModal()">Close</button>
  `);
}

async function deletePurchase(hangoutId, pid) {
  await api('finance_del_purchase', { hangoutId, pid });
  renderFinance();
}

async function deleteHangout(id) {
  if (!confirm('Delete this hangout and all its purchases?')) return;
  await api('finance_del_hangout', { id });
  renderFinance();
}
