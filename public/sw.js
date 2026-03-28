// Simple Service Worker for PWA installation
self.addEventListener('install', (event) => {
    console.log('Service Worker installed');
});

self.addEventListener('fetch', (event) => {
    // Basic fetch handler (required for PWA)
    event.respondWith(
        fetch(event.request).catch(() => {
            return new Response('Offline');
        })
    );
});
