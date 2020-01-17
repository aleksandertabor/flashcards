importScripts('https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js');
if (workbox) {

    // top-level routes we want to precache
    workbox.precaching.precacheAndRoute(['/']);

    // injected assets by Workbox CLI
    workbox.precaching.precacheAndRoute([]);

    // Cache main SPA html for all routes
    workbox.routing.registerNavigationRoute(
        // Assuming '/' has been precached,
        // look up its corresponding cache key.
        workbox.precaching.getCacheKeyForURL('/')
    );

    // Cache GraphQL API requests
    workbox.routing.registerRoute(
        RegExp('(graphql?)'),
        new workbox.strategies.NetworkFirst({
            cacheName: 'api-cache',
        })
    );


    // Cache the Google Fonts stylesheets with a stale-while-revalidate strategy.
    workbox.routing.registerRoute(
        /^https:\/\/fonts\.googleapis\.com/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'google-fonts-stylesheets',
        })
    );

    workbox.routing.registerRoute(
        /^https:\/\/fonts\.gstatic\.com/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'google-fonts-stylesheets',
        })
    );


    // js/css files
    workbox.routing.registerRoute(
        RegExp('.+\.(js|css|woff2|woff|ttf)'),
        new workbox.strategies.CacheFirst({
            cacheName: 'fonts-styles-cache',
        })
    );

    // images
    workbox.routing.registerRoute(
        RegExp('.+\.(png|jpg|jpeg|webp)'),
        new workbox.strategies.CacheFirst({
            cacheName: 'images-cache',
            plugins: [
                new workbox.expiration.Plugin({
                    // Cache upto 50 images.
                    maxEntries: 50,
                    // Cache for a maximum of a week.
                    maxAgeSeconds: 7 * 24 * 60 * 60,
                })
            ],
        })
    );

}
