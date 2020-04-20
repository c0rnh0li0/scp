importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.1.2/workbox-sw.js');

async function deleteCacheAndMetadata(cacheName) {
    await caches.delete(cacheName);
    const cacheExpiration = new CacheExpiration(cacheName);
    cacheExpiration.delete();
}

self.__precacheManifest = [
    { "url": "/", revision: null},
    //{ "url": "/offline", revision: null},
    //{ "url": "/about", revision: null},
    { "url": "/js/app.js", revision: null},
    { "url": "/css/app.css", revision: null},
];

if (workbox) {
    console.log(`Yay! Workbox is loaded 🎉`);
    console.log(workbox);

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
                    statuses: [0, 200]
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