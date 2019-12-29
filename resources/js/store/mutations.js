const mutations = {
    login(state, payload) {
        state.user = payload;
        // state.user.isAuthenticated = true;
    },
    logout(state) {
        state.user.email = null;
        // state.user.isAuthenticated = false;
    },
    decks(state, payload) {
        console.log(payload);
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
