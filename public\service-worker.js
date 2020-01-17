importScripts('https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js');
if (workbox) {

    // top-level routes we want to precache
    workbox.precaching.precacheAndRoute(['/']);

    // injected assets by Workbox CLI
    workbox.precaching.precacheAndRoute([
  {
    "url": "/0.js",
    "revision": "9bb21cddfe2d572801c8aa2b3ffeefc3"
  },
  {
    "url": "/1.js",
    "revision": "b96b1fdf7d1ce8ad2201d42a55cbd176"
  },
  {
    "url": "/2.js",
    "revision": "ab1bf67d8d3bf3d69d3da89330cb2c59"
  },
  {
    "url": "/3.js",
    "revision": "320ff74bdedab09e7912fceaa7ec418e"
  },
  {
    "url": "/css/app.css",
    "revision": "5734d5c4fdac8d9e9c55b64a7c34fe86"
  },
  {
    "url": "/favicon.ico",
    "revision": "2a64701304999353492fd8c41ece6204"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.eot",
    "revision": "3ac49cb33f43a6471f21ab3df40d1b1e"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.svg",
    "revision": "d2e53334c22a9a4937bc26e84b36e1e0"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.ttf",
    "revision": "ece54318791c51b52dfdc689efdb6271"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.woff",
    "revision": "a57bcf76c178aee452db7a57b75509b6"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-regular-400.woff2",
    "revision": "9efb86976bd53e159166c12365f61e25"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.eot",
    "revision": "7fb1cdd9c3b889161216a13267b55fe2"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.svg",
    "revision": "7a5de9b08012e4da40504f2cf126a351"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.ttf",
    "revision": "2aa6edf8f296a43b32df35f330b7c81c"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.woff",
    "revision": "93f284548b42ab76fe3fd03a9d3a2180"
  },
  {
    "url": "/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.woff2",
    "revision": "f6121be597a72928f54e7ab5b95512a1"
  },
  {
    "url": "/fonts/vendor/@mdi/materialdesignicons-webfont.eot",
    "revision": "f81583fc4d8aa5aa2820976561ea8aec"
  },
  {
    "url": "/fonts/vendor/@mdi/materialdesignicons-webfont.ttf",
    "revision": "1618c77b6c5c11926819a8868a083031"
  },
  {
    "url": "/fonts/vendor/@mdi/materialdesignicons-webfont.woff",
    "revision": "d6e3eba9b16453f51d4ef6e216bf89ff"
  },
  {
    "url": "/fonts/vendor/@mdi/materialdesignicons-webfont.woff2",
    "revision": "927457ed7478ec7e1580a2b88116f9c3"
  },
  {
    "url": "/img/5/leicesterlogo-1-230x230.jpg",
    "revision": "562413007f0e15fdea38b4abc2748463"
  },
  {
    "url": "/img/icons/icon-128x128.png",
    "revision": "11c551c2981b1d299f5a2e5761515754"
  },
  {
    "url": "/img/icons/icon-144x144.png",
    "revision": "c15fac59454a02e8d20035ec29990c23"
  },
  {
    "url": "/img/icons/icon-152x152.png",
    "revision": "cf92ed4ce19bc7eed2f3da0344792a24"
  },
  {
    "url": "/img/icons/icon-192x192.png",
    "revision": "a385b294366358735296d6947037d72e"
  },
  {
    "url": "/img/icons/icon-384x384.png",
    "revision": "95d264560224bb0c14a469bc9345fb34"
  },
  {
    "url": "/img/icons/icon-512x512.png",
    "revision": "9a72d588678eefe18b3356f831c69417"
  },
  {
    "url": "/img/icons/icon-72x72.png",
    "revision": "3be7a72aec631600d4355d8ea93d8082"
  },
  {
    "url": "/img/icons/icon-96x96.png",
    "revision": "8cc003eee4f132bd83d92154c219f65a"
  },
  {
    "url": "/img/placeholder.png",
    "revision": "4788048f319dc48101678d9e69f5077e"
  },
  {
    "url": "/js/app.js",
    "revision": "dba5bdb2100202dfd8683d920d9d6a9a"
  },
  {
    "url": "/js/manifest.js",
    "revision": "c51ec4b22fbe3c4b68b16cf4beab1428"
  },
  {
    "url": "/js/vendor.js",
    "revision": "9f26c8da5dfe19fb7b7f2993d2190a78"
  },
  {
    "url": "/mix-manifest.json",
    "revision": "207fd484b7c2ceeff7800b8c8a11b3b6"
  },
  {
    "url": "/vendor/telescope/app-dark.css",
    "revision": "5ebb5e33b7c425d6317c2bd6399891ae"
  },
  {
    "url": "/vendor/telescope/app.css",
    "revision": "326b997a579e007d2102d90439db5379"
  },
  {
    "url": "/vendor/telescope/app.js",
    "revision": "f2e12e6d70f933613e34a7324996c70b"
  },
  {
    "url": "/vendor/telescope/favicon.ico",
    "revision": "a903f931a3fcbcb88c8c8ab8d5b189b8"
  },
  {
    "url": "/vendor/telescope/mix-manifest.json",
    "revision": "03c3c0bd42d4e068f9e31493d9351c14"
  }
]);

    //!     NetworkFirst - Routes will try to use the network first and fall back on to the cache.
    //! StaleWhileRevalidate - JS and CSS files will first use the cache and then try to update the files from the network if available.
    //! CacheFirst - Images will favour the cache and not try to get the image from the network unless the cache has expired.


    // Cache the Google Fonts stylesheets with a stale-while-revalidate strategy.
    workbox.routing.registerRoute(
        /^https:\/\/fonts\.googleapis\.com/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'google-fonts-stylesheets',
        })
    );

    // match routes for homepage, blog and any sub-pages of blog
    // workbox.routing.registerRoute(
    //     /^\/(?:(deck)?(\/.*)?)$/,
    //     new workbox.strategies.NetworkFirst({
    //         cacheName: 'static-resources',
    //     })
    // );

    // workbox.routing.registerRoute(
    //     /^\/(?:(user)?(\/.*)?)$/,
    //     new workbox.strategies.NetworkFirst({
    //         cacheName: 'static-resources',
    //     })
    // );

    // js/css files
    workbox.routing.registerRoute(
        /\.(?:js|css|json)$/,
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'static-resources',
        })
    );

    // images
    workbox.routing.registerRoute(
        // Cache image files.
        /\.(?:png|jpg|jpeg|svg|gif|webp|ttf|woff|woff2|eot|ico)$/,
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
                })
            ],
        })
    );

}
