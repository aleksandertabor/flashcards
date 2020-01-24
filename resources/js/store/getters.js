const getters = {
    decks: state => {
        return state.decks
    },
    cards: state => {
        return state.cards
    },
    user: state => {
        return state.user
    },
    isAuthenticated: state => {
        return !!state.user.access_token
    },
    token: state => {
        return state.user.access_token
    },
    expiry: state => {
        return state.user.expires_in
    },
    started: state => {
        return state.started
    },
}

export default getters;
