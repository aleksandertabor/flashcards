export default {
    api: {
        namespaced: true,
        actions: {
            ...require("@/store/actions/apisActions").default
        }
    },

    decks: {
        namespaced: true,
        state: {
            ...require("@/store/states/decksState").default
        },
        mutations: {
            ...require("@/store/mutations/decksMutations").default
        },
        actions: {
            ...require("@/store/actions/decksActions").default
        },
        getters: {
            ...require("@/store/getters/decksGetters").default
        }
    },

    auth: {
        namespaced: true,
        state: {
            ...require("@/store/states/authState").default
        },
        mutations: {
            ...require("@/store/mutations/authMutations").default
        },
        actions: {
            ...require("@/store/actions/authActions").default
        },
        getters: {
            ...require("@/store/getters/authGetters").default
        }
    },

    editor: {
        namespaced: true,
        actions: {
            ...require("@/store/actions/editorActions").default
        }
    },

    profile: {
        namespaced: true,
        actions: {
            ...require("@/store/actions/profileActions").default
        }
    }
}
