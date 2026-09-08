let plansCache = [];
let calCursor = new Date();
let selectedDate = null;

async function renderPlans() {
  const view = document.querySelector('[data-tab="plans"]');
  view.innerHTML = `<div class="muted">Loading...</div>`;
  const res = await api('plans_list', {}, 'POST');
  plansCache = res.data || [];

  // Default selected = today (if in current month view) or 1st
  if (!selectedDate) selectedDate = todayISO();

  addFab(() => openPlanModal(selectedDate));
  drawCalendar();
}

function todayISO() {
  const d = new Date();
  return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
}

function drawCalendar() {
  const view = document.querySelector('[data-tab="plans"]');
  const y = calCursor.getFullYear();
  const m = calCursor.getMonth();
  const monthName = calCursor.toLocaleString('en-US', { month: 'long', year: 'numeric' });

  const firstDay = new Date(y, m, 1).getDay(); // 0 Sun ... 6 Sat
  const daysInMonth = new Date(y, m + 1, 0).getDate();
  const todayStr = todayISO();

  // Map of date → count
  const counts = {};
  plansCache.forEach(p => {
    counts[p.date] = (counts[p.date] || 0) + 1;
  });

  // Build cells: 6 rows × 7 cols
  const cells = [];
  for (let i = 0; i < firstDay; i++) cells.push({ empty: true });
  for (let d = 1; d <= daysInMonth; d++) {
    const ds = `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    cells.push({
      day: d,
      date: ds,
      isToday: ds === todayStr,
      isSelected: ds === selectedDate,
      count: counts[ds] || 0
    });
  }
  while (cells.length % 7 !== 0) cells.push({ empty: true });

  const weekdays = ['S','M','T','W','T','F','S'];

  const dayPlans = plansCache
    .filter(p => p.date === selectedDate)
    .sort((a, b) => (a.createdAt || '').localeCompare(b.createdAt || ''));

  const view_el = document.querySelector('[data-tab="plans"]');
  view_el.innerHTML = `
    <div class="cal-header">
      <button class="cal-nav" id="cal-prev">‹</button>
      <div class="cal-month" id="cal-month">${monthName}</div>
      <button class="cal-nav" id="cal-next">›</button>
    </div>
    <div class="cal-weekdays">
      ${weekdays.map(w => `<div>${w}</div>`).join('')}
    </div>
    <div class="cal-grid">
      ${cells.map(c => {
        if (c.empty) return `<div class="cal-cell empty"></div>`;
        const dots = c.count > 3 ? 3 : c.count;
        return `
          <div class="cal-cell ${c.isSelected ? 'selected' : ''} ${c.isToday ? 'today' : ''}" data-date="${c.date}">
            <span class="cal-day">${c.day}</span>
            ${c.count ? `<span class="cal-dots">${'●'.repeat(dots)}</span>` : ''}
          </div>
        `;
      }).join('')}
    </div>

    <div class="cal-selected">
      <div class="cal-selected-head">
        <div>
          <div class="cal-selected-date">${fmtNiceDate(selectedDate)}</div>
          <div class="muted">${dayPlans.length} ${dayPlans.length === 1 ? 'plan' : 'plans'}</div>
        </div>
        <button class="btn btn-sm" onclick="openPlanModal('${selectedDate}')">+ Add</button>
      </div>
      ${dayPlans.length ? dayPlans.map(planItem).join('') : `
        <div class="empty" style="padding:32px 12px">
          <div style="font-size:32px">✨</div>
          <div class="muted">Nothing planned yet — tap Add</div>
        </div>
      `}
    </div>
  `;

  document.getElementById('cal-prev').addEventListener('click', () => {
    calCursor = new Date(y, m - 1, 1);
    drawCalendar();
  });
  document.getElementById('cal-next').addEventListener('click', () => {
    calCursor = new Date(y, m + 1, 1);
    drawCalendar();
  });
  document.getElementById('cal-month').addEventListener('click', () => {
    calCursor = new Date();
    selectedDate = todayISO();
    drawCalendar();
  });
  view_el.querySelectorAll('.cal-cell[data-date]').forEach(c => {
    c.addEventListener('click', () => {
      selectedDate = c.dataset.date;
      drawCalendar();
    });
  });
  view_el.querySelectorAll('.plan-item').forEach(el => {
    const id = el.dataset.id;
    el.querySelector('.plan-toggle').addEventListener('click', () => togglePlan(id));
    el.querySelector('.plan-del').addEventListener('click', () => deletePlan(id));
  });
}

function planItem(p) {
  return `
    <div class="plan-item ${p.done ? 'done' : ''}" data-id="${p.id}">
      <button class="plan-toggle" title="${p.done ? 'Undo' : 'Done'}">
        ${p.done ? '✅' : '⚪'}
      </button>
      <div class="plan-item-body">
        <div class="plan-item-title">${esc(p.title)}</div>
        ${p.location || p.notes ? `<div class="plan-item-meta">${p.location ? '📍 ' + esc(p.location) : ''}${p.location && p.notes ? ' · ' : ''}${p.notes ? esc(p.notes) : ''}</div>` : ''}
      </div>
      <button class="plan-del btn-icon" title="Delete">${ICON.trash}</button>
    </div>
  `;
}

function fmtNiceDate(iso) {
  const d = new Date(iso + 'T00:00:00');
  return d.toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' });
}

async function togglePlan(id) {
  await api('plans_toggle', { id });
  renderPlans();
}

async function deletePlan(id) {
  if (!confirm('Delete this plan?')) return;
  await api('plans_delete', { id });
  renderPlans();
}

function openPlanModal(date) {
  openModal(`
    <h2>New plan · ${fmtNiceDate(date)}</h2>
    <div class="form-group">
      <label>Title</label>
      <input class="input" id="plan-title" placeholder="e.g. Dinner at Sushi Zen" autofocus />
    </div>
    <div class="form-group">
      <label>Date</label>
      <input type="date" class="input" id="plan-date" value="${date}" />
    </div>
    <div class="form-group">
      <label>Location (optional)</label>
      <input class="input" id="plan-loc" placeholder="e.g. Sunway Pyramid" />
    </div>
    <div class="form-group">
      <label>Notes (optional)</label>
      <textarea class="textarea" id="plan-notes"></textarea>
    </div>
    <div class="row">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn" onclick="savePlan()">Add plan</button>
    </div>
  `);
  setTimeout(() => document.getElementById('plan-title')?.focus(), 100);
}

async function savePlan() {
  const title = document.getElementById('plan-title').value.trim();
  const date = document.getElementById('plan-date').value;
  if (!title || !date) { toast('Title and date required'); return; }
  const location = document.getElementById('plan-loc').value.trim();
  const notes = document.getElementById('plan-notes').value.trim();
  await api('plans_add', { title, date, location, notes, createdBy: App.user });
  closeModal();
  selectedDate = date;
  // If the plan is in another month, jump the calendar there
  const dObj = new Date(date + 'T00:00:00');
  calCursor = new Date(dObj.getFullYear(), dObj.getMonth(), 1);
  renderPlans();
}

function addFab(handler) {
  const fab = document.createElement('button');
  fab.className = 'fab';
  fab.textContent = '+';
  fab.addEventListener('click', handler);
  document.body.appendChild(fab);
}
