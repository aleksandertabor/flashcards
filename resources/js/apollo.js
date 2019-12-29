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
    app
} from "./app.js";

// HTTP connection to the API
const httpLink = createHttpLink({
    // You should use an absolute URL here
    uri: 'http://localhost:3000/api/graphql',
    headers: {
        // 'X-CSRF-TOKEN': Cookies.get('csrftoken'),
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
})

const errorLink = onError(({
    graphQLErrors,
    networkError
}) => {
    if (graphQLErrors)
        for (let err of graphQLErrors) {
            console.log(err);
            switch (err.extensions.category) {
                case 'authentication':
                    app.$router.push({
                        name: 'login'
                    })
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

const link = ApolloLink.from([
    errorLink,
    httpLink,
]);

// Cache implementation
const cache = new InMemoryCache()

// Create the apollo client
const apolloClient = new ApolloClient({
    link,
    cache,
})

export default apolloClient;
