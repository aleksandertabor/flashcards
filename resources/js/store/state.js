const state = {
    decks: [],
    cards: [],
    errors: [],
    hasPrevious: false,
    user: JSON.parse(localStorage.getItem('user')) || {
        username: null,
        email: null,
        token: null
    }
}

export default state;
