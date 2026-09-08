async function renderToday() {
  const view = document.querySelector('[data-tab="today"]');
  view.innerHTML = `<div class="muted" style="text-align:center">Loading...</div>`;

  const [todayRes, actRes] = await Promise.all([
    api('today_get', {}, 'POST'),
    api('activity_feed', { limit: 20 }, 'POST')
  ]);
  const data = todayRes.data || { since: null, myMood: null, bfMood: null, question: null, answers: {}, replies: [] };
  const events = actRes.data || [];

  const daysTogether = data.since
    ? Math.max(1, Math.floor((Date.now() - new Date(data.since)) / 86400000))
    : null;

  const moods = ['😊','😍','🥰','😌','😴','😢','😤','🤒'];
  const myMood = App.user === 'me' ? data.myMood : data.bfMood;
  const partnerMood = App.user === 'me' ? data.bfMood : data.myMood;
  const partnerKey = App.user === 'me' ? 'bf' : 'me';
  const partnerName = App.users[partnerKey].emoji + ' ' + App.users[partnerKey].name;

  view.innerHTML = `
    <div class="dash">

      <div class="today-hero">
        ${daysTogether !== null
          ? `<p class="today-days">${daysTogether}</p><p class="today-label">days together 💗</p>`
          : `<p class="today-label">Set your anniversary to start counting</p>
             <button class="btn btn-sm" style="margin-top:12px" onclick="setAnniversary()">Set date</button>`}
      </div>

      <div class="card center">
        <h3>How are you feeling?</h3>
        <div class="mood-picker">
          ${moods.map(m => `<button class="mood-btn ${m === myMood ? 'selected' : ''}" data-mood="${m}">${m}</button>`).join('')}
        </div>
        <p class="muted" style="margin:0">
          ${partnerMood ? `${partnerName}: ${partnerMood}` : `${partnerName} hasn't shared a mood`}
        </p>
      </div>

      <div class="card">
        <h3 class="center">💭 Question of the day</h3>
        <p class="qod-question center">${esc(data.question || 'Loading a question...')}</p>

        <div class="qod-answers">
          ${data.answers?.me ? `
            <div class="qod-bubble bubble-me">
              <div class="qod-who">${App.users.me.emoji} ${App.users.me.name}</div>
              <div class="qod-text">${esc(data.answers.me)}</div>
            </div>` : ''}
          ${data.answers?.bf ? `
            <div class="qod-bubble bubble-bf">
              <div class="qod-who">${App.users.bf.emoji} ${App.users.bf.name}</div>
              <div class="qod-text">${esc(data.answers.bf)}</div>
            </div>` : ''}
        </div>

        ${!data.answers?.[App.user] ? `
          <textarea class="textarea" id="qod-answer" placeholder="Your answer..." style="margin-top:10px"></textarea>
          <button class="btn btn-block" style="margin-top:8px" onclick="submitAnswer()">Answer</button>
        ` : ''}

        ${(data.answers?.me || data.answers?.bf) ? `
          <div class="qod-replies">
            ${(data.replies || []).map(r => `
              <div class="qod-bubble bubble-${r.user}">
                <div class="qod-who">${App.users[r.user]?.emoji} ${App.users[r.user]?.name} <span class="muted" style="font-weight:400">· ${fmtTimeAgo(r.at)}</span></div>
                <div class="qod-text">${esc(r.text)}</div>
              </div>
            `).join('')}
            <div class="qod-reply-row">
              <input class="input" id="qod-reply" placeholder="Reply..." />
              <button class="btn btn-sm" onclick="sendQodReply()">Send</button>
            </div>
          </div>
        ` : ''}
      </div>

      <div class="card">
        <h3 class="center">📊 Activity</h3>
        ${events.length ? `
          <div class="activity-list">
            ${events.map(activityRow).join('')}
          </div>
        ` : `<p class="muted center" style="margin:10px 0">Nothing here yet — add a plan, snap a photo, or log a purchase.</p>`}
      </div>

      <div class="card center anniv-card">
        <h3>⚙️ Anniversary</h3>
        <p class="muted">${data.since ? fmtDate(data.since) : 'Not set'}</p>
        <button class="btn btn-ghost btn-sm" onclick="setAnniversary()">${data.since ? 'Change' : 'Set'}</button>
      </div>

    </div>
  `;

  view.querySelectorAll('.mood-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      const mood = btn.dataset.mood;
      await api('today_set_mood', { user: App.user, mood });
      renderToday();
    });
  });
}

function activityRow(e) {
  const who = e.user ? `${App.users[e.user]?.emoji || ''} <strong>${App.users[e.user]?.name || ''}</strong>` : '';
  return `
    <div class="act-row">
      <div class="act-icon">${e.icon}</div>
      <div class="act-body">
        <div class="act-line">
          ${who} ${esc(e.text)}
          ${e.detail ? `<span class="act-detail">"${esc(e.detail)}"</span>` : ''}
        </div>
        <div class="act-meta">
          ${fmtTimeAgo(e.at)}${e.sub ? ' · ' + esc(e.sub) : ''}
        </div>
      </div>
    </div>
  `;
}

async function submitAnswer() {
  const val = document.getElementById('qod-answer').value.trim();
  if (!val) return;
  await api('today_answer', { user: App.user, answer: val });
  renderToday();
}

async function sendQodReply() {
  const el = document.getElementById('qod-reply');
  const text = el.value.trim();
  if (!text) return;
  el.value = '';
  await api('today_reply', { user: App.user, text });
  renderToday();
}

function setAnniversary() {
  openModal(`
    <h2>Anniversary date</h2>
    <div class="form-group">
      <label>When did you two become official?</label>
      <input type="date" class="input" id="anniv-date" />
    </div>
    <div class="row">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn" onclick="saveAnniversary()">Save</button>
    </div>
  `);
}

async function saveAnniversary() {
  const d = document.getElementById('anniv-date').value;
  if (!d) return;
  await api('today_set_anniversary', { date: d });
  closeModal();
  renderToday();
}
