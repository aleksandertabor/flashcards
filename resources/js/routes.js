import VueRouter from "vue-router";
import ExampleComponent from "./components/ExampleComponent"
import Flashcards from "./flashcards/Flashcards"

const routes = [{
        path: "/",
        component: Flashcards,
        name: "home",
    },
    {
        path: "/second",
        component: ExampleComponent,
        name: "second",
    }
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

export default router;
