require('./bootstrap');

import router from "./routes";
import VueRouter from "vue-router";
import Index from "./Index";

window.Vue = require('vue');

Vue.use(VueRouter);


const app = new Vue({
    el: '#app',
    router,
    components: {
        "index": Index
    }
});
