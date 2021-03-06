importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.0.0/workbox-sw.js');
if (workbox) {
    workbox.setConfig({
        debug: false
    })
    workbox.core.skipWaiting();
    workbox.core.clientsClaim();

    // top-level routes we want to precache
    workbox.precaching.precacheAndRoute(['/']);

    // injected assets by Workbox CLI
    workbox.precaching.precacheAndRoute([]);

    workbox.precaching.precacheAndRoute(self.__WB_MANIFEST);

    workbox.precaching.cleanupOutdatedCaches()

    // Cache main SPA html for all routes
    const handler = workbox.precaching.createHandlerBoundToURL('/');
    // Assuming '/' has been precached,
    // look up its corresponding cache key.
    const navigationRoute = new workbox.routing.NavigationRoute(handler, {
        denylist: [
            new RegExp('/img/*'),
            new RegExp('/api/*'),
        ],
    });
    workbox.routing.registerRoute(navigationRoute);


    // Cache GraphQL API requests
    workbox.routing.registerRoute(
        RegExp('(graphql?)'),
        new workbox.strategies.NetworkFirst({
            cacheName: 'api-cache',
        })
    );


    // Cache the Google Fonts stylesheets with a stale-while-revalidate strategy.
    workbox.routing.registerRoute(
        /.*(?:googleapis|gstatic)\.com.*$/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'google-fonts',
        })
    );


    // images
    workbox.routing.registerRoute(
        RegExp('.+\.(png|jpg|jpeg|webp)'),
        new workbox.strategies.CacheFirst({
            cacheName: 'images-cache',
            plugins: [
                new workbox.expiration.ExpirationPlugin({
                    // Cache upto 60 images.
                    maxEntries: 60,
                    // Cache for a maximum of a month.
                    maxAgeSeconds: 30 * 24 * 60 * 60,
                    // Automatically cleanup if quota is exceeded.
                    purgeOnQuotaError: true,
                })
            ],
        })
    );

    self.addEventListener('notificationclick', function (e) {
        var notification = e.notification;
        var action_url = notification.data.action_url;
        var action = e.action;

        console.log(e);

        switch (action) {
            case 'close':
                notification.close();
                break;
            case 'explore':
                e.waitUntil(
                    clients.matchAll({
                        type: 'window',
                        includeUncontrolled: true
                    })
                    .then(function (clientList) {
                        var client = clientList.find(function (c) {
                            // if has opened browser
                            return c.visibilityState === 'visible';
                        });

                        if (client !== undefined) {
                            // has opened browser
                            client.navigate(action_url);
                            client.focus();
                        } else {
                            // no opened browser so open a new window
                            clients.openWindow(action_url);
                        }
                    })
                );
                break;
            default:
                clients.openWindow(action_url);
                break;
        }

        notification.close();
    });

    self.addEventListener('notificationclose', function (e) {
        var notification = e.notification;
        var action_url = notification.data.action_url;

        console.log(':(((( You dont like our content. Closed notification: ' + action_url);
    });


    self.addEventListener('push', function (e) {
        if (!(self.Notification && self.Notification.permission === 'granted')) {
            //notifications aren't supported or permission not granted!
            return;
        }

        if (e.data) {
            var msg = e.data.json();
            console.log(msg)
            e.waitUntil(self.registration.showNotification(msg.title, {
                body: msg.body,
                image: msg.image,
                icon: msg.icon,
                lang: msg.lang,
                dir: msg.dir,
                actions: msg.actions,
                badge: msg.badge,
                requireInteraction: msg.requireInteraction,
                data: msg.data
            }));
        }
    });

}
