require('./bootstrap');

import Vuex from "vuex";
import router from "./routes";
import VueRouter from "vue-router";
import LazyLoad from "./lazyload";
import Index from "./Index";
import VueApollo from "vue-apollo";
import apolloClient from "./apollo";

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(LazyLoad);
Vue.use(Vuex);
Vue.use(VueApollo);
import mutations from './store/mutations';
import actions from './store/actions';
import getters from './store/getters';
import state from "./store/state";

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
})

const store = new Vuex.Store({
    state,
    mutations,
    actions,
    getters
});


Vue.mixin({
    computed: {
        isAuthenticated() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${state.user.token}`;
            return this.$store.getters.isAuthenticated;
        },
        user() {
            return this.$store.getters.user;
        }
    },
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.authenticated)) {
        if (!store.getters.isAuthenticated) {
            next({
                name: 'login',
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.visitor)) {
        if (store.getters.isAuthenticated) {
            next({
                name: 'home',
            })
        } else {
            next()
        }
    } else {
        next()
    }
})


const app = new Vue({
    el: '#app',
    store,
    router,
    apolloProvider,
    components: {
        "index": Index
    },
    // //? add to mixins?
    // beforeCreate() {
    //     this.$store.dispatch("checkUser");
    // },
});
