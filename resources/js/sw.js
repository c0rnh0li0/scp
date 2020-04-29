importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.1.2/workbox-sw.js');

importScripts('https://www.gstatic.com/firebasejs/7.13.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.13.0/firebase-messaging.js');

const firebaseConfig = {
    apiKey: "AIzaSyDrKxNprd3CrfaSzH_u__tPzpUNd-aRpmU",
    authDomain: "skopje-city-pass.firebaseapp.com",
    databaseURL: "https://skopje-city-pass.firebaseio.com",
    projectId: "skopje-city-pass",
    storageBucket: "skopje-city-pass.appspot.com",
    messagingSenderId: "332397838213",
    appId: "1:332397838213:web:487455021fbd85cee88eec",
    measurementId: "G-KL1YXRDH6Q"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    console.log('background notification received', payload)
    const notificationOptions = buildNotificationOptions(payload);
    return self.registration.showNotification(payload.data.title, notificationOptions);
});

self.addEventListener('notificationclick', function(event) {
    console.log('background notification clicked', event)
    event.notification.close();
    var promise = new Promise(function(resolve) {
        setTimeout(resolve, 3000);
    }).then(function() {
        return processNotification(event.notification);
    });
    event.waitUntil(promise);
});

self.addEventListener('notificationclose', function(event) {
    console.log('background notification closed', event)
    console.log("notification close in sw", event);
});

function processNotification(notification) {
    var sAction = notification.data.action;
    var sUrl = notification.data.url;

    return clients.matchAll({
        includeUncontrolled: true,
        type: 'window'
    }).then(function(clis) {
        var client = clis.find(function(c) {
            c.visibilityState === 'visible';
        });
        if (client !== undefined) {
            client.navigate(sUrl);
            return client.focus();
        } else {
            return clients.openWindow(sUrl);
        }
    });
}

function buildNotificationOptions(payload) {
    var options = {};
    /* Object */
    options.data = payload.data;
    if (typeof payload.data.body != 'undefined')
        options.body = payload.data.body;
    /* String */
    if (typeof payload.data.icon != 'undefined')
        options.icon = payload.data.icon;
    /* String */
    if (typeof payload.data.badge != 'undefined')
        options.badge = payload.data.badge;
    /* String */
    if (typeof payload.data.image != 'undefined')
        options.image = payload.data.image;
    /* String */
    if (typeof payload.data.tag != 'undefined')
        options.tag = payload.data.tag;
    /* Array of integers */
    if (typeof payload.data.vibrate != 'undefined')
        options.vibrate = payload.data.vibrate;
    /* String */
    if (typeof payload.data.sound != 'undefined')
        options.sound = payload.data.sound;
    /* Boolean */
    if (typeof payload.data.requireInteraction != 'undefined')
        options.requireInteraction = (typeof payload.data.requireInteraction != 'undefined' ? payload.data.requireInteraction : false);
    /* Boolean */
    if (typeof payload.data.renotify != 'undefined')
        options.renotify = (typeof payload.data.renotify != 'undefined' ? payload.data.renotify : false);
    /* Boolean */
    if (typeof payload.data.silent != 'undefined')
        options.silent = (typeof payload.data.silent != 'undefined' ? payload.data.silent : false);
    return options;
}


/* workbox from here on */

async function deleteCacheAndMetadata(cacheName) {
    await caches.delete(cacheName);
    const cacheExpiration = new CacheExpiration(cacheName);
    cacheExpiration.delete();
}

self.__precacheManifest = [
    { "url": "/", revision: null},
    { "url": "/offline", revision: null},
    { "url": "/about", revision: null},
    { "url": "/js/app.js", revision: null},
    { "url": "/css/app.css", revision: null},
    { "url": "/manifest.json", revision: null},
];

if (workbox) {
    console.log(`Yay! Workbox is loaded 🎉`);

    workbox.setConfig({
        debug: false
    });
    workbox.precaching.precacheAndRoute(self.__precacheManifest, {
        directoryIndex: null
    });

    var networkOnly = new workbox.strategies.NetworkOnly({
        cacheName: 'online-pages',
        networkTimeoutSeconds: 10,
    });

    workbox.routing.registerRoute(
        /\.(?:png|jpg|jpeg|svg|gif)$/,
        new workbox.strategies.CacheFirst({
            cacheName: 'image-cache',
            plugins: [
                new workbox.expiration.ExpirationPlugin({
                    maxEntries: 300,
                    maxAgeSeconds: 7 * 24 * 60 * 60 * 60,
                    purgeOnQuotaError: false
                })
            ],
        })
    );

    workbox.routing.registerRoute(
        /\/api\/.*?$/,
        new workbox.strategies.NetworkFirst({
            cacheName: 'api-cache',
            plugins: [
                new workbox.expiration.ExpirationPlugin({
                    maxEntries: 300,
                    maxAgeSeconds: 7 * 24 * 60 * 60,
                    purgeOnQuotaError: false
                }),
                new workbox.cacheableResponse.CacheableResponsePlugin({
                    statuses: [0, 200, 201]
                }),
            ],
        })
    );


    // Cache css and js files with a stale-while-revalidate strategy.
    workbox.routing.registerRoute(
        /\.(js|css)$/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'js-css-cache',
        })
    );

    // Avoid caching Google Maps
    workbox.routing.registerRoute(
        /https:\/\/khms\d\.googleapis\.com\/.*?$/,
        new workbox.strategies.NetworkOnly({
            cacheName: 'google-maps',
        })
    );
    workbox.routing.registerRoute(
        /https:\/\/maps\.googleapis\.com\/.*?\/(ViewportInfoService).*?$/,
        new workbox.strategies.NetworkOnly({
            cacheName: 'google-maps',
        })
    );



    // Cache css and js files with a stale-while-revalidate strategy.
    workbox.routing.registerRoute(
        /\/fonts\/.*?$/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'fonts-cache',
        })
    );


    // Cache the Google Fonts stylesheets with a stale-while-revalidate strategy.
    workbox.routing.registerRoute(
        /https:\/\/fonts\.googleapis\.com\/.*?$/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'google-fonts-stylesheets',
        })
    );
    // Cache the underlying font files with a cache-first strategy for 1 year.
    workbox.routing.registerRoute(
        /https:\/\/fonts\.gstatic\.com\/.*?$/,
        new workbox.strategies.CacheFirst({
            cacheName: 'google-fonts-webfonts',
            plugins: [
                new workbox.cacheableResponse.CacheableResponsePlugin({
                    statuses: [0, 200],
                }),
                new workbox.expiration.ExpirationPlugin({
                    maxAgeSeconds: 60 * 60 * 24 * 365,
                    maxEntries: 30,
                    purgeOnQuotaError: false
                }),
            ],
        })
    );
    var defaultStrategy = new workbox.strategies.NetworkFirst({
        cacheName: "default-cache",
        plugins: [
            new workbox.expiration.ExpirationPlugin({
                maxEntries: 300,
                maxAgeSeconds: 7 * 24 * 60 * 60,
                purgeOnQuotaError: false
            }),
            new workbox.cacheableResponse.CacheableResponsePlugin({
                statuses: [0, 200]
            }),
        ],
    });

    workbox.routing.setDefaultHandler(
        (args) => {
            console.log('request: ', args.event.request.url);
            if (args.event.request.method === 'GET') {
                return defaultStrategy.handle(args); // use default strategy
            }
            var req = args.event.request.clone();
            return fetch(args.event.request)
                .catch(function() {
                    console.log('searching in cache (catch)', args.event.request.url);
                    const cachedRespCatch = caches.match(args.event.request);
                    if (cachedRespCatch) {
                        console.log('found in cache (catch)', args.event.request.url);
                        return cachedRespCatch;
                    }
                    console.log('NOT found in cache (catch)', args.event.request.url);
                    if (!navigator.onLine) {
                        args.event.respondWith(new Response("Network error occurred", {
                            "status": 300,
                            "headers": {
                                "Content-Type": "text/plain"
                            }
                        }));
                        args.event.preventDefault();
                        return false;
                    }
                });
        }
    );
    // This "catch" handler is triggered when any of the other routes fail to
    // generate a response.
    workbox.routing.setCatchHandler(({
                                         event
                                     }) => {
        switch (event.request.destination) {
            case 'document':
                return caches.match(workbox.precaching.getCacheKeyForURL('/offline'));
                break;
            case 'unknown':
                return caches.match(workbox.precaching.getCacheKeyForURL('/offline'));
                break;
            case 'image':
                return caches.match(workbox.precaching.getCacheKeyForURL('inject_image_not_found_url'));
                break;
            default:
                // If we don't have a fallback, just return an error response.
                console.log('error catch hander', event);
                //return Response.error();
                return new Response("Network error occurred", {
                    "status": 300,
                    "headers": {
                        "Content-Type": "text/plain"
                    }
                });
        }
    });
}