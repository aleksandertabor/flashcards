const state = {
    decks: [],
    decksPage: 1,
    query: "",
    cards: [],
    errors: [],
    user: JSON.parse(localStorage.getItem('user')) || {},
}

export default state;
