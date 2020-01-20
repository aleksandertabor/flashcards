import {
    Workbox
} from 'workbox-window';

const serviceWorkerStart = function () {
    if ('serviceWorker' in navigator) {
        const wb = new Workbox('/service-worker.js');
        wb.addEventListener('activated', (event) => {
            // Get the current page URL + all resources the page loaded.
            const urlsToCache = [
                location.href,
                ...performance.getEntriesByType('resource').map((r) => r.name),
            ];

            // Send that list of URLs to your router in the service worker.
            wb.messageSW({
                type: 'CACHE_URLS',
                payload: {
                    urlsToCache
                },
            });
        });

        wb.addEventListener('installed', event => {
            if (event.isUpdate) {
                if (confirm(`Our app was updated! Click OK to refresh`)) {
                    window.location.reload();
                }
            }
        });

        wb.register();
    }
}

const notificationSystemStart = function () {
    if ('Notification' in window && navigator.serviceWorker) {
        initPush();
    }

    function initPush() {
        if (!navigator.serviceWorker.ready) {
            return;
        }

        new Promise(function (resolve, reject) {
                const permissionResult = Notification.requestPermission(function (result) {
                    resolve(result);
                });

                if (permissionResult) {
                    permissionResult.then(resolve, reject);
                }
            })
            .then((permissionResult) => {
                if (permissionResult !== 'granted') {
                    throw new Error('We weren\'t granted permission.');
                }
                subscribeUser();
            });
    }

    function subscribeUser() {
        navigator.serviceWorker.ready
            .then((registration) => {
                const subscribeOptions = {
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(
                        process.env.MIX_VAPID_PUBLIC_KEY
                    )
                };

                return registration.pushManager.subscribe(subscribeOptions);
            })
            .then((pushSubscription) => {
                console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
                storePushSubscription(pushSubscription);
            });
    }

    function storePushSubscription(pushSubscription) {
        const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

        console.log(pushSubscription);

        fetch('/api/push', {
                method: 'POST',
                body: JSON.stringify(pushSubscription),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': token
                }
            })
            .then((res) => {
                return res.json();
            })
            .then((res) => {
                console.log(res)
            })
            .catch((err) => {
                console.log(err)
            });
    }


    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');
        const rawData = window.atob(base64);
        return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
    }
}

export {
    serviceWorkerStart,
    notificationSystemStart
}
