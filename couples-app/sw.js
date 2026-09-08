const CACHE = 'us-app-v7';
const CORE = [
  './',
  'index.html',
  'css/style.css',
  'js/app.js',
  'js/today.js',
  'js/games.js',
  'js/plans.js',
  'js/feed.js',
  'js/finance.js',
  'manifest.json'
];

self.addEventListener('install', (e) => {
  e.waitUntil(caches.open(CACHE).then(c => c.addAll(CORE)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (e) => {
  e.waitUntil(caches.keys().then(keys =>
    Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k)))
  ).then(() => self.clients.claim()));
});

self.addEventListener('fetch', (e) => {
  const url = new URL(e.request.url);
  // Never cache API or uploads — always fetch fresh
  if (url.pathname.includes('/api/') || url.pathname.includes('/uploads/') || url.pathname.includes('/data/')) {
    return;
  }
  e.respondWith(
    caches.match(e.request).then(cached =>
      cached || fetch(e.request).then(res => {
        if (res.ok && e.request.method === 'GET') {
          const copy = res.clone();
          caches.open(CACHE).then(c => c.put(e.request, copy));
        }
        return res;
      }).catch(() => cached)
    )
  );
});
