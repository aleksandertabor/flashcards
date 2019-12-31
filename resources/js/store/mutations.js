const mutations = {
    login(state, payload) {
        state.isAuthenticated = true;
    },
    saveToken(state, payload) {
        state.token = payload.token;
        state.expiry = payload.expiry;
    },
    me(state, payload) {
        state.user = payload;
    },
    logout(state) {
        state.user.username = null;
        state.user.email = null;
        state.isAuthenticated = false;
    },
    decks(state, payload) {
        state.decks.push.apply(state.decks, payload)
    },
    resetDecks(state) {
        state.decks = []
    },
    resetDecksPage(state) {
        state.decksPage = 1;
    },
    decksPageIncrement(state) {
        state.decksPage++;
    },
    query(state, payload) {
        state.query = payload;
    },
    search(state, payload) {
        state.decks = payload;
        state.decksPage = 1;
    },
}

export default mutations;
