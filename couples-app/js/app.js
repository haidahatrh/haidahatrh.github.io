// ---------- Global state ----------
const App = {
  user: null,
  users: {
    me: { name: 'Athirah', emoji: '💗', color: '#f472b6' },
    bf: { name: 'Naqib', emoji: '💙', color: '#60a5fa' }
  },
  API: 'api/',
  currentTab: 'today'
};

// ---------- Icons ----------
const ICON = {
  trash: `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1.5 14a2 2 0 0 1-2 1.8h-7a2 2 0 0 1-2-1.8L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>`
};

// ---------- API helper ----------
async function api(action, payload = {}, method = 'POST') {
  const opts = {
    method,
    headers: method === 'POST' ? { 'Content-Type': 'application/json' } : {}
  };
  if (method === 'POST') opts.body = JSON.stringify({ action, ...payload });
  const url = App.API + 'index.php' + (method === 'GET' ? '?action=' + action : '');
  try {
    const res = await fetch(url, opts);
    return await res.json();
  } catch (e) {
    toast('Network error');
    return { ok: false, error: e.message };
  }
}

async function apiUpload(action, formData) {
  formData.append('action', action);
  try {
    const res = await fetch(App.API + 'index.php', { method: 'POST', body: formData });
    return await res.json();
  } catch (e) {
    toast('Upload failed');
    return { ok: false };
  }
}

// ---------- Toast ----------
function toast(msg, ms = 1800) {
  const t = document.createElement('div');
  t.className = 'toast';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), ms);
}

// ---------- Modal helper ----------
function openModal(html) {
  const backdrop = document.createElement('div');
  backdrop.className = 'modal-backdrop';
  backdrop.innerHTML = `<div class="modal">${html}</div>`;
  document.body.appendChild(backdrop);
  backdrop.addEventListener('click', (e) => {
    if (e.target === backdrop) closeModal();
  });
  return backdrop;
}
function closeModal() {
  document.querySelectorAll('.modal-backdrop').forEach(m => m.remove());
}

// ---------- Login ----------
document.querySelectorAll('.login-buttons button').forEach(btn => {
  btn.addEventListener('click', () => {
    const u = btn.dataset.user;
    localStorage.setItem('us_user', u);
    startApp(u);
  });
});

function startApp(user) {
  App.user = user;
  document.getElementById('login-screen').classList.add('hidden');
  document.getElementById('app').classList.remove('hidden');
  document.getElementById('header-user').textContent = App.users[user].emoji + ' ' + App.users[user].name;
  switchTab('today');
}

// ---------- Tab switching ----------
function switchTab(tab) {
  App.currentTab = tab;
  document.querySelectorAll('.nav-item').forEach(n => {
    n.classList.toggle('active', n.dataset.tab === tab);
  });
  document.querySelectorAll('.tab-view').forEach(v => {
    v.classList.toggle('hidden', v.dataset.tab !== tab);
  });
  const titles = { today: 'Today', games: 'Games', plans: 'Plans', feed: 'Feed', finance: 'Finance' };
  document.getElementById('header-title').textContent = titles[tab];
  // Remove any leftover FAB
  document.querySelectorAll('.fab').forEach(f => f.remove());
  // Load tab
  if (tab === 'today') renderToday();
  else if (tab === 'games') renderGames();
  else if (tab === 'plans') renderPlans();
  else if (tab === 'feed') renderFeed();
  else if (tab === 'finance') renderFinance();
}

document.querySelectorAll('.nav-item').forEach(item => {
  item.addEventListener('click', () => switchTab(item.dataset.tab));
});

// ---------- Utils ----------
function fmtDate(iso) {
  const d = new Date(iso);
  return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}
function fmtTimeAgo(iso) {
  const s = Math.floor((Date.now() - new Date(iso)) / 1000);
  if (s < 60) return 'just now';
  if (s < 3600) return Math.floor(s/60) + 'm ago';
  if (s < 86400) return Math.floor(s/3600) + 'h ago';
  const d = Math.floor(s/86400);
  if (d < 7) return d + 'd ago';
  return fmtDate(iso);
}
function money(n) {
  return 'RM ' + Number(n).toFixed(2);
}
function esc(s) {
  return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

// ---------- Boot ----------
const saved = localStorage.getItem('us_user');
if (saved && (saved === 'me' || saved === 'bf')) {
  startApp(saved);
}
