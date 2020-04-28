import {
    Workbox
} from 'workbox-window';

export default {
    install: function (Vue, name = "$ServiceWorker") {
        if ('serviceWorker' in navigator) {
            Vue.prototype.$wb = new Workbox('/service-worker.js');

            let serviceWorker = Vue.observable({
                hasUpdated: false
            })

            Vue.mixin({
                computed: {
                    $ServiceWorkerUpdated: {
                        get() {
                            return serviceWorker.hasUpdated
                        },
                        set(status) {
                            serviceWorker.hasUpdated = status
                        }
                    }
                },
            })

            Vue.prototype.$ServiceWorkerActivated = function () {
                    this.$wb.addEventListener('activated', (event) => {
                        // Get the current page URL + all resources the page loaded.
                        const urlsToCache = [
                            location.href,
                            ...performance.getEntriesByType('resource').map((r) => r.name),
                        ];

                        // Send that list of URLs to your router in the service worker.
                        this.$wb.messageSW({
                            type: 'CACHE_URLS',
                            payload: {
                                urlsToCache
                            },
                        });
                    });
                },
                Vue.prototype.$ServiceWorkerInstalled = function () {
                    this.$wb.addEventListener('installed', event => {
                        if (event.isUpdate) {
                            this.$ServiceWorkerUpdated = true;
                            window.location.reload();
                        }
                    });
                },
                Vue.prototype.$ServiceWorkerRegister = function () {
                    this.$wb.register();
                }
        }
    }

};
