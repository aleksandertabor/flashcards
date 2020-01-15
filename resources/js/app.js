require('./bootstrap');
import Vuex from "vuex";
import router from "./routes";
import VueRouter from "vue-router";
import LazyLoad from "./lazyload";
import Index from "./Index";
import VueApollo from "vue-apollo";
import apolloClient from "./apollo";
import Vuetify from 'vuetify'
import vuetify from "./vuetify";

window.Vue = require('vue');

Vue.use(Vuetify)
Vue.use(VueRouter);
Vue.use(LazyLoad);
Vue.use(Vuex);
Vue.use(VueApollo);
import mutations from './store/mutations';
import actions from './store/actions';
import getters from './store/getters';
import state from "./store/state";



const store = new Vuex.Store({
    state,
    mutations,
    actions,
    getters
});

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
})


Vue.mixin({
    computed: {
        isAuthenticated() {
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



export const app = new Vue({
    el: '#app',
    store,
    router,
    apolloProvider,
    vuetify,
    // components: {
    //     "index": Index
    // },
    components: {
        "index": Index
    },
    // //? add to mixins?
    methods: {
        onStorageUpdate(event) {
            if (event.key === "user") {
                // if someone removes elsewhere user item
                if (event.newValue === null) {
                    this.logout();
                }
            }
            if (event.key === "logout") {
                console.log("Logout");
                this.logout();
            }
        },
        auth() {
            return new Promise((resolve, reject) => {
                if (this.$store.getters.token) {
                    const expiry = this.$store.getters.expiry;
                    // if expired - lesser than minute
                    if (new Date(new Date(expiry) - 1 * 60000) <= new Date(expiry)) {
                        this.$store
                            .dispatch("refresh_token")
                            .then(response => {
                                // this.$store.dispatch("me");
                            })
                            .catch(error => {
                                this.logout();
                            })
                    }
                } else {
                    // if no token
                    this.$store
                        .dispatch("refresh_token")
                        .then(response => {
                            this.$store.dispatch("me");
                            this.$router.push({
                                name: "home"
                            });
                        }).catch(error => {
                            // what if no token and no refresh_token??
                            console.log("You are guest.");
                        })


                }
                resolve(true)
            });
        },
        logout() {
            this.$store.dispatch('forceLogout');
            this.$router.push({
                name: "login"
            });
        }
    },
    created() {

    },
    // beforeCreate() {
    //     window.localStorage.removeItem('logout')
    // },
    mounted() {
        this.auth().then(res => {
            this.authInterval = setInterval(() => this.auth(), 60000);
        });

        window.addEventListener("storage", this.onStorageUpdate);

    },
    beforeDestroy() {
        clearInterval(this.authInterval)
        window.removeEventListener('storage', this.onStorageUpdate)
        window.localStorage.removeItem('logout')
    },
});
