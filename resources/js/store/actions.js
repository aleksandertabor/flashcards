import apolloClient from "../apollo";
import {
    login,
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
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })

        });
    },
    register(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/register", payload)
                .then(response => {
                    const user = response.data;
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
                    const user = response.data.me;
                    context.commit('login', user);
                    localStorage.setItem('user', JSON.stringify(user))
                    console.log("graphql response", response);
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

        if (context.getters.isAuthenticated) {
            return new Promise((resolve, reject) => {
                apolloClient.mutate({
                        mutation: logout,
                    })
                    .then(response => {
                        console.log({
                            response
                        });
                        localStorage.removeItem('user');
                        context.commit('logout');
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })

            });
        }

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
                    }
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
                    }
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
