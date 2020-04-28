export async function getXsrfToken() {

    const name = "XSRF-TOKEN";

    const cookie = getCookie(name);

    if (cookie) {
        return Promise.resolve(cookie);
    } else {
        try {
            await axios("/api/csrf-cookie");
            return Promise.resolve(getCookie(name));
        } catch (error) {}
    }
}

function getCookie(name) {
    var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
    return (match ? decodeURIComponent(match[3]) : null);
}
