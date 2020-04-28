import "core-js/stable";
import "regenerator-runtime/runtime";
import 'whatwg-fetch';
require('./httpClients/axios');
import store from "./config/store";
import router from "./config/routes";
import VueRouter from "vue-router";
import ServiceWorker from "./plugins/ServiceWorker";
import NotificationSystem from "./plugins/NotificationSystem";
import Index from "./views/layouts/Index.vue";
import VueApollo from "vue-apollo";
import apolloClient from "./httpClients/apollo/apolloClient";
import vuetify from "./config/vuetify";
import VueClipboard from 'vue-clipboard2'
import Loading from './shared/components/Loading.vue';

window.Vue = require('vue');

if ('Notification' in window) {
    Vue.use(NotificationSystem);
}

Vue.use(VueRouter);
Vue.use(ServiceWorker);
Vue.use(VueApollo);
Vue.use(VueClipboard)

Vue.component('loading', Loading);


const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
})

import {
    isLoggedInRefresh,
    currentUser
} from "@/shared/utils/auth"

import {
    isEmpty
} from "lodash";

Vue.mixin({
    computed: {
        isLoggedIn() {
            return this.$store.getters['auth/isLoggedIn'] || isLoggedInRefresh();
        },
        user() {
            return !isEmpty(this.$store.getters['auth/user']) ? this.$store.getters['auth/user'] : false || currentUser();
        }
    },
})

import multiTabsLogout from "./shared/mixins/multiTabsLogoutMixin";

export const app = new Vue({
    el: '#app',
    store,
    router,
    apolloProvider,
    vuetify,
    components: {
        "index": Index
    },
    mixins: [multiTabsLogout],
    async beforeCreate() {
        this.$store.dispatch("auth/loadStoredState");

        this.$store.dispatch('auth/user');

        if ('serviceWorker' in navigator) {
            this.$ServiceWorkerActivated();
            this.$ServiceWorkerInstalled();
            this.$ServiceWorkerRegister();
        }

        if ('Notification' in window && navigator.serviceWorker) {
            this.$NotificationsInstall();
        }
    },
})
