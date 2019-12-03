import VueRouter from "vue-router";
import Decks from "./decks/Decks";
import Deck from "./deck/Deck";

const routes = [{
        path: "/",
        component: Decks,
        name: "home",
    },
    {
        path: "/deck/:id",
        component: Deck,
        name: "deck"
    },
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

export default router;
