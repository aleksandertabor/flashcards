const mutations = {
    login(state, payload) {
        state.user = payload;
        // state.user.isAuthenticated = true;
    },
    logout(state) {
        state.user.token = null;
        // state.user.isAuthenticated = false;
    },
    search(state, payload) {
        state.decks = payload;
    },
}

export default mutations;
