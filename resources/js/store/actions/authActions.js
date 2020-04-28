import apolloClient from "@/httpClients/apollo/apolloClient";
import {
    LOGIN,
    REGISTER,
    USER,
    LOGOUT
} from "@/queries/auth.gql";
import {
    isLoggedIn
} from "@/shared/utils/auth"

export default {
    loadStoredState(context) {
        context.commit('setLoggedIn', isLoggedIn());
    },
    async login(context, payload) {
        try {
            const user = (await apolloClient.mutate({
                mutation: LOGIN,
                variables: {
                    data: payload
                }
            })).data.login;

            context.dispatch("setUser", user)

            return Promise.resolve(user)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async register(context, payload) {
        try {
            const user = (await apolloClient.mutate({
                mutation: REGISTER,
                variables: {
                    data: payload
                }
            })).data.register;

            context.dispatch("setUser", user)

            return Promise.resolve(user)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async user(context) {
        if (isLoggedIn()) {
            try {
                const user = (await apolloClient.query({
                    query: USER,
                })).data.user;

                if (user) {
                    context.dispatch("setUser", user)
                }

            } catch (errors) {
                context.dispatch("forceLogout")
            }
        }
    },
    async logout(context) {
        try {
            const response = (await apolloClient.mutate({
                mutation: LOGOUT,
            })).data.logout;

            context.dispatch("forceLogout")

            return Promise.resolve(response)
        } catch (errors) {
            context.dispatch("forceLogout");

            return Promise.reject(errors)
        }
    },
    setUser({
        commit
    }, user) {
        commit('setUser', user);
        commit('setLoggedIn', true);

        localStorage.setItem('isLoggedIn', true)
        localStorage.setItem('user', JSON.stringify(user))
    },
    forceLogout(context) {
        context.commit('setLoggedIn', false);
        context.commit('setUser', {});

        window.App.user = {};
        window.App.isLoggedIn = false;
        localStorage.setItem('isLoggedIn', false)
        localStorage.removeItem('user')
    },

};
