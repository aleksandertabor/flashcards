import {
    ApolloClient
} from 'apollo-client'
import {
    createHttpLink
} from 'apollo-link-http'
import {
    onError
} from "apollo-link-error";
import {
    ApolloLink
} from 'apollo-link';
import {
    InMemoryCache
} from 'apollo-cache-inmemory'
import {
    setContext
} from 'apollo-link-context';
import {
    app
} from "./app.js";

// HTTP connection to the API
const httpLink = createHttpLink({
    // You should use an absolute URL here
    uri: 'http://localhost:8000/api/graphql',
    headers: {
        // 'X-CSRF-TOKEN': Cookies.get('csrftoken'),
        // 'Authorization': `Bearer ${app.$store.getters.token}`,
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name=csrf-token]').getAttribute('content')
    },
    // Make all requests to API with GET method - helpful with PWA caching
    fetchOptions: {
        method: "GET",
    },
})

const errorLink = onError(({
    graphQLErrors,
    networkError,
}) => {
    if (graphQLErrors)
        for (let err of graphQLErrors) {
            console.log(err);
            switch (err.extensions.category) {
                case 'authentication':
                    // app.$router.push({
                    //     name: 'login'
                    // })
                    // graphQLErrors.validationErrors['password'] = err.message;
                    console.log("No authenticated.");
                case 'validation':
                    graphQLErrors.validationErrors = err.extensions.validation;
                    // return err.extensions.validation;
                    // console.log('validation errors', err.extensions.validation);
            }
        }
    if (networkError) console.log(`[Network error]: ${networkError}`);
});

const authLink = setContext((_, {
    headers
}) => {
    // get the authentication token from local storage if it exists
    let storageUser = JSON.parse(localStorage.getItem('user')) || {};
    let token = "";
    if (Object.entries(storageUser).length !== 0 && storageUser.constructor === Object) {
        token = storageUser.access_token;
    } else {
        token = app.$store.getters.token;
    }
    console.log("wysylam ten token do bearera: ", token);
    // return the headers to the context so httpLink can read them
    return {
        headers: {
            ...headers,
            authorization: token ? `Bearer ${token}` : '',
        },
    };
});

const link = ApolloLink.from([
    errorLink,
    authLink,
    httpLink,
]);

// Cache implementation
const cache = new InMemoryCache({
    addTypename: false
})

// Disable caching
const defaultOptions = {
    watchQuery: {
        fetchPolicy: 'no-cache',
        errorPolicy: 'ignore',
    },
    query: {
        fetchPolicy: 'no-cache',
        errorPolicy: 'all',
    },
}

// Create the apollo client
const apolloClient = new ApolloClient({
    link,
    cache,
    defaultHttpLink: false,
    connectToDevTools: true,
    defaultOptions
})

export default apolloClient;
