import VueRouter from "vue-router";
import Decks from "./decks/Decks";
import Deck from "./deck/Deck";
import Login from "./auth/Login"
import Register from "./auth/Register"
import DeckEditor from "./editor/DeckEditor"

const routes = [{
        path: "/",
        component: Decks,
        name: "home",
    },
    {
        path: "/login",
        component: Login,
        name: "login",
    },
    {
        path: "/register",
        component: Register,
        name: "register",
    },
    // {
    //     path: '/user/:id',
    //     component: User,
    //     children: [{
    //             path: 'profile',
    //             component: UserProfile
    //         },
    //         {
    //             path: 'decks',
    //             component: UserPosts
    //         }
    //     ]
    // },
    {
        path: "/deck/editor",
        component: DeckEditor,
        name: "deck-editor",
    },
    {
        path: "/deck/:slug",
        component: Deck,
        name: "deck",
    },
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

export default router;
