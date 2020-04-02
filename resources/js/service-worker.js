// FILE PATH: /resources/js/service-worker.js

importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.1.2/workbox-sw.js');
importScripts('https://www.gstatic.com/firebasejs/7.13.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.13.1/firebase-analytics.js');

firebase.initializeApp({
    apiKey: "AIzaSyDrKxNprd3CrfaSzH_u__tPzpUNd-aRpmU",
    authDomain: "skopje-city-pass.firebaseapp.com",
    databaseURL: "https://skopje-city-pass.firebaseio.com",
    projectId: "skopje-city-pass",
    storageBucket: "skopje-city-pass.appspot.com",
    messagingSenderId: "332397838213",
    appId: "1:332397838213:web:487455021fbd85cee88eec",
    measurementId: "G-KL1YXRDH6Q"
});

firebase.initializeApp(firebaseConfig);
firebase.analytics();

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    const notification = JSON.parse(payload.data.data);
    const notificationTitle = notification.title;

    const notificationOptions = {
        body: notification.body,
        icon: '/android-chrome-144x144.png',
    };

    return self.registration.showNotification(notificationTitle,
        notificationOptions);
});

if (workbox) {
    console.log(`Yay! Workbox is loaded 🎉`);
    // injected assets by Workbox CLI
    workbox.precaching.precacheAndRoute([]);

    // js/css files
    workbox.routing.registerRoute(
        /\.(?:js|css)$/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'static-resources',
        })
    );

    // // images
    workbox.routing.registerRoute(
        // Cache image files.
        /\.(?:png|jpg|jpeg|svg|gif)$/,
        // Use the cache if it's available.
        new workbox.strategies.CacheFirst({
            // Use a custom cache name.
            cacheName: 'image-cache',
            plugins: [
                new workbox.expiration.Plugin({
                    // Cache upto 50 images.
                    maxEntries: 50,
                    // Cache for a maximum of a week.
                    maxAgeSeconds: 7 * 24 * 60 * 60,
                }),
                new workbox.cacheableResponse.Plugin({
                    statuses: [200]
                })
            ],
        })
    );

    const networkFirstHandler = new workbox.strategies.NetworkFirst({
        cacheName: 'dynamic',
        plugins: [
            new workbox.expiration.Plugin({
                maxEntries: 10
            }),
            new workbox.cacheableResponse.Plugin({
                statuses: [200]
            })
        ]
    });

    const FALLBACK_URL = workbox.precaching.getCacheKeyForURL('/offline.html');
    const matcher = ({ event }) => event.request.mode === 'navigate';
    const handler = args =>
        networkFirstHandler
            .handle(args)
            .then(response => response || caches.match(FALLBACK_URL))
            .catch(() => caches.match(FALLBACK_URL));

    workbox.routing.registerRoute(matcher, handler);

    self.addEventListener('message', function (event) {
        console.log('user request for update... (' + event.data.action + ')');
        if (event.data.action === 'skipWaiting') {
            self.skipWaiting();
        }
    });
}