const CACHE_NAME = 'medicalds-offline-v1';
const OFFLINE_PAGE = '/offline.html';
const PRECACHE_URLS = [
  OFFLINE_PAGE,
  '/',
  '/visitor/welcome_ru',
  '/visitor/home_ru',
  '/css/app.css',
  '/js/app.js',
  '/mock/cases.json'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

function requestHasSimulateCookie(request) {
  try {
    const cookieHeader = request.headers.get('cookie') || '';
    return cookieHeader.indexOf('simulate_offline=1') !== -1;
  } catch (e) {
    return false;
  }
}

self.addEventListener('fetch', (event) => {
  const req = event.request;

  // Only intercept GET requests
  if (req.method !== 'GET') return;

  // If request is for mock data endpoints and simulate cookie present, return mock JSON
  try {
    const url = new URL(req.url);
    if (requestHasSimulateCookie(req) && (url.pathname.startsWith('/mock/') || url.pathname.includes('/visitor/cases') || url.pathname.includes('/api/clinical_cases') || url.pathname.includes('/api/cases'))) {
      event.respondWith(caches.match('/mock/cases.json'));
      return;
    }
  } catch (e) {
    // ignore malformed URL
  }

  // If simulate_offline cookie is present, serve offline fallback for navigations
  if (req.mode === 'navigate' && requestHasSimulateCookie(req)) {
    event.respondWith(caches.match(OFFLINE_PAGE));
    return;
  }

  // Navigations: try network then cache then offline
  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req).then((res) => {
        return res;
      }).catch(() => {
        return caches.match(req).then((cached) => cached || caches.match(OFFLINE_PAGE));
      })
    );
    return;
  }

  // For other resources: cache-first then network, then offline fallback
  event.respondWith(
    caches.match(req).then((cached) => {
      if (cached) return cached;
      return fetch(req).then((response) => {
        // Put a copy in cache (best-effort)
        return caches.open(CACHE_NAME).then((cache) => {
          try { cache.put(req, response.clone()); } catch (e) { /* ignore opaque */ }
          return response;
        });
      }).catch(() => caches.match(OFFLINE_PAGE));
    })
  );
});
