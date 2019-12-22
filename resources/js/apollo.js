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

// HTTP connection to the API
const httpLink = createHttpLink({
    // You should use an absolute URL here
    uri: 'http://localhost:3000/api/graphql',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    }
})

const errorLink = onError(({
    graphQLErrors,
    networkError
}) => {
    if (graphQLErrors)
        for (let err of graphQLErrors) {
            switch (err.extensions.category) {
                case 'authentication':
                    vm.$router.push({
                        name: 'auth.login'
                    })
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
