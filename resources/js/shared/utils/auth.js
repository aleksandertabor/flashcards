export function isLoggedInRefresh() {
    return window.App.isLoggedIn;
}

export function currentUser() {
    return window.App.user;
}

export function isLoggedIn() {
    return localStorage.getItem("isLoggedIn") == 'true';
}
