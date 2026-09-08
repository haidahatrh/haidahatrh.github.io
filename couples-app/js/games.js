const GAMES = {
  wyr: {
    emoji: '🤔',
    title: 'Would You Rather',
    desc: 'Two options, hard choice.',
    prefix: 'Would you rather... ',
    items: [
      'travel the world for a year OR own your dream home?',
      'never use social media again OR never eat your favorite food again?',
      'always be 10 minutes late OR always be 20 minutes early?',
      'know when you\'ll die OR how you\'ll die?',
      'have unlimited money OR unlimited time?',
      'be able to teleport anywhere OR read minds?',
      'live without music OR without movies?',
      'sleep 4 hours a night with no tiredness OR sleep 10 hours and feel great?',
      'have a private jet OR a private chef forever?',
      'be famous OR be rich but unknown?'
    ]
  },
  truth: {
    emoji: '💬',
    title: 'Deep Questions',
    desc: 'Truths to bring you closer.',
    prefix: '',
    items: [
      'What\'s one thing you\'ve never told me but want to?',
      'What did you think when you first saw me?',
      'What\'s your biggest fear about us?',
      'Which of my habits do you secretly love?',
      'What\'s a small dream you\'ve never shared?',
      'If we could travel anywhere tomorrow, where?',
      'What song reminds you of us?',
      'What\'s the sweetest thing I\'ve ever done?',
      'When did you know you loved me?',
      'What do you want our future to look like in 5 years?',
      'What\'s something you\'ve forgiven me for that I don\'t know about?',
      'What\'s your love language really?'
    ]
  },
  dare: {
    emoji: '😈',
    title: 'Playful Dares',
    desc: 'Small silly challenges.',
    prefix: '',
    items: [
      'Send your partner a voice note singing a love song.',
      'Send a selfie doing your best goofy face.',
      'Text your partner a haiku about them.',
      'Compliment your partner in 3 different languages.',
      'Draw a portrait of your partner in 60 seconds and send it.',
      'Do 10 push-ups and send video proof.',
      'Send an old childhood photo of yourself.',
      'Say something cheesy in your best movie voice.',
      'Send a photo of what you\'re doing right now.',
      'Write a love note and hide it somewhere they\'ll find later.'
    ]
  },
  quiz: {
    emoji: '📝',
    title: 'How Well Do You Know Me',
    desc: 'Guess your partner\'s answers.',
    prefix: '',
    items: [
      'My favorite comfort food is...',
      'The place I\'d most like to visit is...',
      'My biggest pet peeve is...',
      'My dream job as a kid was...',
      'My favorite way to relax is...',
      'The last movie I really loved was...',
      'If I won 1 million ringgit, first thing I\'d buy...',
      'My favorite memory of us is...',
      'My hidden talent is...',
      'My guilty pleasure is...'
    ]
  }
};

let currentGame = null;
let currentPool = [];   // combined built-in + custom
let usedIndexes = [];
let customQuestions = {}; // { wyr: [{id, text, by}], ... }

async function renderGames() {
  const view = document.querySelector('[data-tab="games"]');
  view.innerHTML = `<div class="muted">Loading...</div>`;
  const res = await api('games_list', {}, 'POST');
  customQuestions = res.data || { wyr: [], truth: [], dare: [], quiz: [] };

  view.innerHTML = Object.entries(GAMES).map(([key, g]) => {
    const customCount = (customQuestions[key] || []).length;
    return `
      <div class="game-card" data-game="${key}">
        <div class="game-emoji">${g.emoji}</div>
        <div class="game-title">${g.title}</div>
        <div class="game-desc">${g.desc}</div>
        <div class="game-meta">
          ${g.items.length} default${customCount ? ` + ${customCount} custom` : ''}
        </div>
      </div>
    `;
  }).join('');

  view.querySelectorAll('.game-card').forEach(c => {
    c.addEventListener('click', () => startGame(c.dataset.game));
  });
}

function startGame(key) {
  currentGame = key;
  const g = GAMES[key];
  const custom = (customQuestions[key] || []).map(q => q.text);
  currentPool = [...g.items, ...custom];
  usedIndexes = [];
  showQuestion();
}

function showQuestion() {
  const g = GAMES[currentGame];
  if (!currentPool.length) {
    renderGameScreen(`<div class="muted" style="text-align:center;padding:20px">No questions yet — add one!</div>`);
    return;
  }
  if (usedIndexes.length >= currentPool.length) usedIndexes = [];
  let idx;
  do { idx = Math.floor(Math.random() * currentPool.length); } while (usedIndexes.includes(idx));
  usedIndexes.push(idx);
  const q = currentPool[idx];
  const isCustom = idx >= g.items.length;

  renderGameScreen(`
    <div class="question-box">
      ${g.prefix}${esc(q)}
      ${isCustom ? '<div class="custom-badge">✨ Custom</div>' : ''}
    </div>
    <button class="btn btn-block" onclick="showQuestion()">Next question ✨</button>
  `);
}

function renderGameScreen(inner) {
  const g = GAMES[currentGame];
  const custom = customQuestions[currentGame] || [];
  const view = document.querySelector('[data-tab="games"]');
  view.innerHTML = `
    <div class="game-top">
      <button class="btn btn-ghost btn-sm" onclick="renderGames()">← Back</button>
      <button class="btn btn-sm" onclick="openAddQuestionModal()">+ Add question</button>
    </div>
    <div style="margin-top:12px">
      <div class="game-emoji" style="font-size:32px;text-align:center">${g.emoji}</div>
      <div style="text-align:center;color:var(--text-dim);margin-bottom:12px">${g.title}</div>
      ${inner}
    </div>
    ${custom.length ? `
      <div style="margin-top:24px">
        <h3 style="margin:0 0 10px;font-size:14px;color:var(--text-dim)">✨ Custom questions (${custom.length})</h3>
        ${custom.map(q => `
          <div class="custom-q-row">
            <div class="custom-q-text">${esc(g.prefix)}${esc(q.text)}</div>
            <div class="custom-q-meta">
              <span class="muted">${App.users[q.by]?.emoji || ''} ${App.users[q.by]?.name || ''}</span>
              <button class="btn-icon" onclick="delCustomQuestion('${q.id}')" title="Delete">${ICON.trash}</button>
            </div>
          </div>
        `).join('')}
      </div>
    ` : ''}
  `;
}

function openAddQuestionModal() {
  const g = GAMES[currentGame];
  const example = g.items[Math.floor(Math.random() * g.items.length)];
  openModal(`
    <h2>Add ${g.title} question</h2>
    <p class="muted" style="margin-top:-8px">${g.prefix ? `Starts with "<em>${g.prefix.trim()}</em>"` : 'Free-form question'}</p>
    <div class="form-group">
      <label>Your question</label>
      <textarea class="textarea" id="q-text" placeholder="${esc(example)}" autofocus></textarea>
    </div>
    <div class="row">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn" onclick="saveCustomQuestion()">Add</button>
    </div>
  `);
  setTimeout(() => document.getElementById('q-text')?.focus(), 100);
}

async function saveCustomQuestion() {
  const text = document.getElementById('q-text').value.trim();
  if (!text) { toast('Type a question'); return; }
  await api('games_add', { game: currentGame, text, by: App.user });
  closeModal();
  toast('Added ✨');
  // Refresh pool
  const res = await api('games_list', {}, 'POST');
  customQuestions = res.data || {};
  const g = GAMES[currentGame];
  const custom = (customQuestions[currentGame] || []).map(q => q.text);
  currentPool = [...g.items, ...custom];
  // Re-render current game screen keeping question if any
  renderGameScreen(`
    <div class="question-box" style="min-height:120px;font-size:16px">
      ✨ Added! Tap next to keep playing.
    </div>
    <button class="btn btn-block" onclick="showQuestion()">Next question ✨</button>
  `);
}

async function delCustomQuestion(id) {
  if (!confirm('Delete this question?')) return;
  await api('games_delete', { game: currentGame, id });
  const res = await api('games_list', {}, 'POST');
  customQuestions = res.data || {};
  const g = GAMES[currentGame];
  const custom = (customQuestions[currentGame] || []).map(q => q.text);
  currentPool = [...g.items, ...custom];
  usedIndexes = [];
  renderGameScreen(`<div class="muted" style="text-align:center;padding:20px">Deleted. Tap next to continue.</div>
    <button class="btn btn-block" onclick="showQuestion()">Next question ✨</button>`);
}
