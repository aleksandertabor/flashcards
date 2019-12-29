const state = {
    decks: [],
    decksPage: 1,
    query: "",
    cards: [],
    errors: [],
    hasPrevious: false,
    user: JSON.parse(localStorage.getItem('user')) || {
        username: null,
        email: null,
    }
}

export default state;
