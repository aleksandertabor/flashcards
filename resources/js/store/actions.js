import gql from "graphql-tag";
import apolloClient from "../apollo";
const actions = {
    login(context, payload) {
        return new Promise((resolve, reject) => {
            // axios
            //     .post("/api/login", payload)
            //     .then(response => {
            //         const user = response.data;
            //         context.commit('login', user);
            //         localStorage.setItem('user', JSON.stringify(user))
            //         resolve(response)
            //     })
            //     .catch(error => {
            //         reject(error)
            //     })
            console.log(payload);
            resolve(
                apolloClient.mutate({
                    mutation: gql `mutation login($data: LoginInput) {
  login(data: $data)
}`,
                    variables: {
                        data: payload
                    }
                }))



        })





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
    checkUser(context) {
        const user = localStorage.getItem('user');
        if (user) {
            context.commit('login', JSON.parse(user));
        }
    },
    logout(context) {

        if (context.getters.isAuthenticated) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/logout")
                    .then(response => {
                        localStorage.removeItem('user');
                        context.commit('logout');
                        delete axios.defaults.headers.common['Authorization']
                        resolve(response)
                    })
                    .catch(error => {
                        localStorage.removeItem('user');
                        context.commit('logout');
                        reject(error)
                    })
            })
        }

    },
    profile(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/users/${payload}`, )
                .then(response => {
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })
        })


    },
    editProfile(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/users/${payload}/edit`, )
                .then(response => {
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })
        })


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
