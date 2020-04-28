import VueRouter from "vue-router";
import Home from "../views/Home";
import Decks from "../views/decks/Decks";
import Deck from "../views/deck/Deck";
import Login from "../views/auth/Login"
import Register from "../views/auth/Register"
import Logout from "../views/auth/Logout"
import DeckEditor from "../views/editor/DeckEditor"
import Profile from "../views/profile/Profile"
import NotFoundComponent from "../shared/components/NotFoundComponent"

const routes = [{
        path: '*',
        component: NotFoundComponent,
    },
    {
        path: "/",
        component: Home,
        name: "home",
    },
    {
        path: "/search",
        component: Decks,
        name: "search",
    },
    {
        path: "/login",
        component: Login,
        name: "login",
        meta: {
            visitor: true
        }
    },
    {
        path: "/register",
        component: Register,
        name: "register",
        meta: {
            visitor: true
        }
    },
    {
        path: "/logout",
        component: Logout,
        name: "logout",
        meta: {
            loggedIn: true
        }
    },
    {
        path: '/user/:username',
        component: Profile,
        name: "profile",
    },
    {
        path: "/editor",
        component: DeckEditor,
        name: "editor",
        props: true,
        meta: {
            loggedIn: true
        }
    },
    {
        path: "/deck/:slug",
        component: Deck,
        name: "deck",
    },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

import {
    isLoggedIn
} from "@/shared/utils/auth";

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.loggedIn)) {
        if (!isLoggedIn()) {
            next({
                name: 'login',
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.visitor)) {
        if (isLoggedIn()) {
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

export default router;
