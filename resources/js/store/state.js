const state = {
    decks: [],
    decksPage: 1,
    query: "",
    cards: [],
    errors: [],
    hasPrevious: false,
    isAuthenticated: false,
    user: JSON.parse(localStorage.getItem('user')) || {
        username: null,
        email: null,
    },
    token: null,
    expiry: null,
}

export default state;
