import {
    getCookie
} from "@/shared/utils/cookies"

export default {
    install: function (Vue, name = "$NotificationSystem") {

        let permission = Vue.observable({
            state: Notification.permission
        })

        Vue.mixin({
            computed: {
                $NotificationsActive: {
                    get() {
                        return permission.state === 'granted'
                    },
                    set(perm) {
                        permission.state = perm
                    }
                }
            },
        })

        Vue.prototype.$NotificationsInstall = function () {
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
                        this.$NotificationsActive = permissionResult
                        this.$NotificationsSubscribe();
                    });
            },
            Vue.prototype.$NotificationsSubscribe = function () {
                navigator.serviceWorker.ready
                    .then((registration) => {
                        const subscribeOptions = {
                            userVisibleOnly: true,
                            applicationServerKey: this.$urlBase64ToUint8Array(
                                process.env.MIX_VAPID_PUBLIC_KEY
                            )
                        };

                        return registration.pushManager.subscribe(subscribeOptions);
                    })
                    .then((pushSubscription) => {
                        this.$NotificationsPushSubscription(pushSubscription);
                    });
            },
            Vue.prototype.$NotificationsUnsubscribe = function () {
                Notification.requestPermission().then(function (result) {
                    console.log(result);
                });
                navigator.serviceWorker.ready.then(registration => {
                    registration.pushManager.getSubscription().then(subscription => {
                        if (!subscription) {
                            // no sub
                            return
                        }
                        subscription.unsubscribe().then(() => {
                            axios.post('/api/unsubscribe', JSON.stringify(subscription), {
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                    }
                                })
                                .then((res) => {})
                                .catch((err) => {
                                    console.log(err)
                                });
                        }).catch(e => {
                            console.log('Unsubscription error: ', e)
                        })
                    }).catch(e => {
                        console.log('Error thrown while unsubscribing.', e)
                    })
                })
            },
            Vue.prototype.$NotificationsPushSubscription = function (pushSubscription) {
                axios.post('/api/push', JSON.stringify(pushSubscription), {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then((res) => {
                        return res;
                    })
                    .then((res) => {})
                    .catch((err) => {
                        console.log(err)
                    });
            },
            Vue.prototype.$urlBase64ToUint8Array = function (base64String) {
                const padding = '='.repeat((4 - base64String.length % 4) % 4);
                const base64 = (base64String + padding)
                    .replace(/\-/g, '+')
                    .replace(/_/g, '/');
                const rawData = window.atob(base64);
                return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
            }
    }

};
