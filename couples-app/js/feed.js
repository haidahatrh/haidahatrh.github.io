async function renderFeed() {
  const view = document.querySelector('[data-tab="feed"]');
  view.innerHTML = `<div class="muted">Loading...</div>`;
  const res = await api('feed_list', {}, 'POST');
  const posts = res.data || [];

  addFab(() => openPostModal());

  if (!posts.length) {
    view.innerHTML = `
      <div class="empty">
        <div class="empty-icon">📸</div>
        <div>No posts yet</div>
        <div class="muted">Tap + to share a moment</div>
      </div>
    `;
    return;
  }

  view.innerHTML = posts.map(postCard).join('');

  view.querySelectorAll('.like-btn').forEach(b => {
    b.addEventListener('click', () => toggleLike(b.dataset.id));
  });
  view.querySelectorAll('.comment-btn').forEach(b => {
    b.addEventListener('click', () => openComments(b.dataset.id));
  });
  view.querySelectorAll('.delete-btn').forEach(b => {
    b.addEventListener('click', () => deletePost(b.dataset.id));
  });
}

function postCard(p) {
  const u = App.users[p.user] || App.users.me;
  const liked = (p.likes || []).includes(App.user);
  const likeCount = (p.likes || []).length;
  const commentCount = (p.comments || []).length;
  const imgs = p.images || [];
  const grid = imgs.length >= 4 ? 'g4' : imgs.length === 3 ? 'g3' : imgs.length === 2 ? 'g2' : 'g1';
  const isMine = p.user === App.user;

  return `
    <div class="feed-post">
      <div class="feed-post-head">
        <div class="avatar">${u.emoji}</div>
        <div style="flex:1">
          <div class="feed-post-user">${u.name}</div>
          <div class="feed-post-time">${fmtTimeAgo(p.createdAt)}</div>
        </div>
        ${isMine ? `<button class="delete-btn btn-icon" data-id="${p.id}" title="Delete">${ICON.trash}</button>` : ''}
      </div>
      ${imgs.length ? `<div class="feed-post-images ${grid}">
        ${imgs.slice(0,4).map(src => `<img src="${esc(src)}" loading="lazy" />`).join('')}
      </div>` : ''}
      ${p.caption ? `<div class="feed-post-body">${esc(p.caption)}</div>` : ''}
      <div class="feed-post-actions">
        <button class="like-btn ${liked ? 'liked' : ''}" data-id="${p.id}">
          ${liked ? '❤️' : '🤍'} ${likeCount || ''}
        </button>
        <button class="comment-btn" data-id="${p.id}">
          💬 ${commentCount || ''}
        </button>
      </div>
    </div>
  `;
}

async function toggleLike(id) {
  await api('feed_like', { id, user: App.user });
  renderFeed();
}

async function deletePost(id) {
  if (!confirm('Delete this post?')) return;
  await api('feed_delete', { id });
  renderFeed();
}

async function openComments(id) {
  const res = await api('feed_get', { id });
  const p = res.data;
  if (!p) return;
  const comments = p.comments || [];
  openModal(`
    <h2>Comments</h2>
    <div id="comments-list" style="max-height:40vh;overflow-y:auto;margin-bottom:12px">
      ${comments.length ? comments.map(c => `
        <div style="padding:10px 0;border-bottom:1px solid var(--border)">
          <strong>${App.users[c.user]?.emoji || ''} ${App.users[c.user]?.name || c.user}:</strong>
          ${esc(c.text)}
          <div class="muted" style="font-size:11px">${fmtTimeAgo(c.at)}</div>
        </div>
      `).join('') : '<div class="muted">No comments yet</div>'}
    </div>
    <textarea class="textarea" id="comment-text" placeholder="Add a comment..."></textarea>
    <div class="row" style="margin-top:8px">
      <button class="btn btn-ghost" onclick="closeModal()">Close</button>
      <button class="btn" onclick="addComment('${id}')">Send</button>
    </div>
  `);
}

async function addComment(id) {
  const text = document.getElementById('comment-text').value.trim();
  if (!text) return;
  await api('feed_comment', { id, user: App.user, text });
  closeModal();
  renderFeed();
}

let pendingFiles = [];

function openPostModal() {
  pendingFiles = [];
  openModal(`
    <h2>New post</h2>
    <div class="form-group">
      <label>Photos (up to 4)</label>
      <div class="pick-row">
        <button type="button" class="pick-btn" id="pick-cam">
          <div style="font-size:24px">📷</div>
          <div>Camera</div>
        </button>
        <button type="button" class="pick-btn" id="pick-gal">
          <div style="font-size:24px">🖼️</div>
          <div>Gallery</div>
        </button>
      </div>
      <input type="file" id="cam-input" accept="image/*" capture="environment" style="display:none" />
      <input type="file" id="gal-input" accept="image/*" multiple style="display:none" />
      <div class="feed-preview" id="post-preview"></div>
    </div>
    <div class="form-group">
      <label>Caption</label>
      <textarea class="textarea" id="post-caption" placeholder="Say something..."></textarea>
    </div>
    <div class="row">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn" id="post-submit" onclick="submitPost()">Post</button>
    </div>
  `);

  document.getElementById('pick-cam').addEventListener('click', () => {
    document.getElementById('cam-input').click();
  });
  document.getElementById('pick-gal').addEventListener('click', () => {
    document.getElementById('gal-input').click();
  });
  document.getElementById('cam-input').addEventListener('change', (e) => addFiles(e.target.files));
  document.getElementById('gal-input').addEventListener('change', (e) => addFiles(e.target.files));
}

function addFiles(fileList) {
  Array.from(fileList).forEach(f => {
    if (pendingFiles.length < 4) pendingFiles.push(f);
  });
  if (fileList.length && pendingFiles.length >= 4) toast('Max 4 photos');
  renderPreview();
}

function renderPreview() {
  const preview = document.getElementById('post-preview');
  if (!preview) return;
  preview.innerHTML = '';
  pendingFiles.forEach((f, idx) => {
    const wrap = document.createElement('div');
    wrap.className = 'preview-item';
    const img = document.createElement('img');
    img.src = URL.createObjectURL(f);
    const rm = document.createElement('button');
    rm.className = 'preview-remove';
    rm.textContent = '×';
    rm.onclick = () => {
      pendingFiles.splice(idx, 1);
      renderPreview();
    };
    wrap.appendChild(img);
    wrap.appendChild(rm);
    preview.appendChild(wrap);
  });
}

async function submitPost() {
  const caption = document.getElementById('post-caption').value.trim();
  if (!pendingFiles.length && !caption) { toast('Add a photo or caption'); return; }
  const btn = document.getElementById('post-submit');
  btn.disabled = true; btn.textContent = 'Posting...';

  const fd = new FormData();
  fd.append('user', App.user);
  fd.append('caption', caption);
  pendingFiles.slice(0, 4).forEach(f => fd.append('images[]', f));

  await apiUpload('feed_add', fd);
  pendingFiles = [];
  closeModal();
  renderFeed();
}
