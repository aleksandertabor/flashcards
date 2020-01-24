import apolloClient from "../apollo";
import apolloUploadClient from "../apolloUploadClient";
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
import {
    deck,
    decks
} from "../queries/deck.gql"
import {
    deckEditor,
    createDeck,
    upload
} from "../queries/editor.gql";
import {
    translate,
    image,
    example
} from "../queries/apis.gql";
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
                    let currentUser = JSON.parse(localStorage.getItem('user')) || {}
                    currentUser.access_token = user.access_token;
                    currentUser.expires_in = user.expires_in;
                    localStorage.setItem('user', JSON.stringify(currentUser))
                    context.commit('refresh', currentUser);
                    resolve(response)
                })
                .catch(error => {
                    console.log("refrest token error", error);
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
                    let user = {
                        username: response.data.me.username,
                        email: response.data.me.email,
                    }
                    let currentUser = JSON.parse(localStorage.getItem('user')) || {}
                    currentUser.username = user.username;
                    currentUser.email = user.email;
                    localStorage.setItem('user', JSON.stringify(currentUser))
                    context.commit('me', currentUser);
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
                        username: payload,
                        isAuthenticated: context.getters.isAuthenticated
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
                        password_confirmation: payload.password_confirmation,
                    },
                })
                .then(response => {
                    const user = response.data.editProfile;
                    let currentUser = JSON.parse(localStorage.getItem('user'))
                    currentUser.username = user.username;
                    currentUser.email = user.email;
                    localStorage.setItem('user', JSON.stringify(currentUser));
                    context.commit('refresh', currentUser);
                    console.log("editProfile", user);
                    resolve(user)
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
                    context.commit('logout');
                    localStorage.removeItem('user')
                    localStorage.setItem('logout', Date.now())
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
            console.log(payload);
            apolloClient.query({
                    query: decks,
                    variables: {
                        amount: payload.amount,
                        page: payload.page,
                        query: payload.query,
                        filter: payload.filter,
                    }
                })
                .then(response => {
                    const decks = response.data.decks.data;
                    if (decks.length) {
                        context.commit('decks', decks);
                    }
                    console.log(response);
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
    deck(context, payload) {
        return new Promise((resolve, reject) => {
            console.log(payload);
            apolloClient.query({
                    query: deck,
                    variables: {
                        slug: payload
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
    deckEditor(context) {
        return new Promise((resolve, reject) => {
            apolloClient.query({
                    query: deckEditor,
                })
                .then(response => {
                    resolve(response);
                })
                .catch(error => {
                    console.log("graphql error", {
                        error
                    });
                    reject(error)
                })

        });
    },
    createDeck(context, payload) {
        console.log("createDeck payload", payload);
        return new Promise((resolve, reject) => {
            apolloUploadClient.mutate({
                    mutation: createDeck,
                    variables: {
                        input: {
                            title: payload.title,
                            description: payload.description,
                            image: payload.image,
                            image_file: payload.image_file,
                            lang_source_id: payload.lang_source_id,
                            lang_target_id: payload.lang_target_id,
                            visibility: payload.visibility.value,
                            cards: payload.cards
                        },
                    }
                })
                .then(response => {
                    console.log("createDeck", response);
                    resolve(response)
                })
                .catch(error => {
                    console.log("createDeck error", error);
                    reject(error)
                })

        });
    },
    translate(context, payload) {
        return new Promise((resolve, reject) => {
            apolloClient.query({
                    query: translate,
                    variables: {
                        input: payload
                    },
                })
                .then(response => {
                    console.log("Translate: ", response);
                    resolve(response);
                })
                .catch(error => {
                    console.log("graphql error", {
                        error
                    });
                    reject(error)
                })

        });
    },
    image(context, payload) {
        return new Promise((resolve, reject) => {
            apolloClient.query({
                    query: image,
                    variables: {
                        input: payload
                    },
                })
                .then(response => {
                    console.log("Image: ", response);
                    resolve(response);
                })
                .catch(error => {
                    console.log("graphql error", {
                        error
                    });
                    reject(error)
                })

        });
    },
    example(context, payload) {
        return new Promise((resolve, reject) => {
            apolloClient.query({
                    query: example,
                    variables: {
                        input: payload
                    },
                })
                .then(response => {
                    console.log("Example: ", response);
                    resolve(response);
                })
                .catch(error => {
                    console.log("graphql error", {
                        error
                    });
                    reject(error)
                })

        });
    },
}

export default actions;
