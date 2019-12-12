require('./bootstrap');

import router from "./routes";
import VueRouter from "vue-router";
import LazyLoad from "./lazyload";
import Index from "./Index";

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(LazyLoad);



const app = new Vue({
    el: '#app',
    router,
    components: {
        "index": Index
    },
});
