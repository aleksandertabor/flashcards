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
        return state.user.token !== null
    }
}

export default getters;
