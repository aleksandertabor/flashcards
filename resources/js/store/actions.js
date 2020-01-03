import apolloClient from "../apollo";
import {
    login,
    refresh_token,
    register,
    me,
    logout
} from "../queries/auth.gql";
import {
    profile,
    editProfile,
    removeProfile
} from "../queries/profile.gql";
const actions = {
    login(context, payload) {
        return new Promise((resolve, reject) => {
            apolloClient.mutate({
                    mutation: login,
                    variables: {
                        data: payload
                    }
                })
                .then(response => {
                    let user = {
                        username: response.data.login.user.username,
                        email: response.data.login.user.email,
                        access_token: response.data.login.access_token,
                        expires_in: response.data.login.expires_in,
                    }
                    console.log("user:", user);
                    context.commit('login', user);
                    localStorage.setItem('user', JSON.stringify(user))
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })

        });
    },
    refresh_token(context) {
        return new Promise((resolve, reject) => {
            apolloClient.mutate({
                    mutation: refresh_token,
                    variables: {
                        refresh: true
                    }
                })
                .then(response => {
                    let user = {
                        access_token: response.data.refresh_token.access_token,
                        expires_in: response.data.refresh_token.expires_in,
                    }
                    let currentUser = JSON.parse(localStorage.getItem('user'))
                    currentUser.access_token = user.access_token;
                    currentUser.expires_in = user.expires_in;
                    localStorage.setItem('user', JSON.stringify(currentUser))
                    context.commit('refresh', currentUser);
                    resolve(response)
                })
                .catch(error => {
                    // console.log("refrest token error", error);
                    reject(error)
                })

        });
    },
    register(context, payload) {
        return new Promise((resolve, reject) => {
            apolloClient.mutate({
                    mutation: register,
                    variables: {
                        data: payload
                    }
                })
                .then(response => {
                    let user = {
                        username: response.data.register.user.username,
                        email: response.data.register.user.email,
                        access_token: response.data.register.access_token,
                        expires_in: response.data.register.expires_in,
                    }
                    context.commit('login', user);
                    localStorage.setItem('user', JSON.stringify(user))
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })
        })
    },
    me(context) {
        return new Promise((resolve, reject) => {
            apolloClient.query({
                    query: me,
                })
                .then(response => {
                    // const user = response.data.me;
                    // context.commit('me', user);
                    // localStorage.setItem('user', JSON.stringify(user))
                    resolve(response)
                })
                .catch(error => {
                    console.log("graphql error", {
                        error
                    });
                    reject(error)
                })

        });
    },
    logout(context) {

        return new Promise((resolve, reject) => {
            apolloClient.mutate({
                    mutation: logout,
                })
                .then(response => {
                    console.log({
                        response
                    });
                    context.commit('logout');
                    localStorage.removeItem('user')
                    localStorage.setItem('logout', Date.now())
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })

        });

    },
    forceLogout(context) {
        context.commit('logout');
        localStorage.removeItem('user')
        // localStorage.setItem('logout', Date.now())
    },
    profile(context, payload) {
        return new Promise((resolve, reject) => {
            console.log(payload);
            apolloClient.query({
                    query: profile,
                    variables: {
                        username: payload
                    }
                })
                .then(response => {
                    resolve(response)
                })
                .catch(error => {
                    console.log("graphql error", {
                        error
                    });
                    reject(error)
                })

        });
    },
    editProfile(context, payload) {
        console.log("edtProfile payload", payload);
        return new Promise((resolve, reject) => {
            apolloClient.mutate({
                    mutation: editProfile,
                    variables: {
                        id: payload.id,
                        username: payload.username,
                        email: payload.email,
                        password: payload.password,
                    },
                })
                .then(response => {
                    console.log("editProfile", response);
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })

        });
    },
    removeProfile(context, payload) {
        return new Promise((resolve, reject) => {
            apolloClient.mutate({
                    mutation: removeProfile,
                    variables: {
                        id: payload.id,
                    },
                })
                .then(response => {
                    console.log("removeProfile res", response);
                    resolve(response)
                })
                .catch(error => {
                    console.log("removeProfile err", error);
                    reject(error)
                })

        });
    },
    decks(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/search/?page=${payload.page}&decks_type=${payload.decksType}&query=${payload.query}`)
                .then(response => {
                    const decks = response.data.data;
                    if (decks.length) {
                        context.commit('decks', decks);
                    }
                    resolve(response.data)
                })
                .catch(error => {
                    reject(error)
                })
        })
    },
    // search(context, payload) {
    //     return new Promise((resolve, reject) => {
    //         axios
    //             .get(`/api/search/?page=${payload.page}&decks_type=${payload.decksType}`)
    //             .then(response => {
    //                 const decks = response.data.data;
    //                 if (decks.length) {
    //                     context.commit('decks', decks);
    //                 }
    //                 resolve(decks.length)
    //             })
    //             .catch(error => {
    //                 reject(error)
    //             })
    //     })


    // },
}

export default actions;
