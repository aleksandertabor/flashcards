import {
    ApolloClient
} from 'apollo-client'
import {
    createHttpLink
} from 'apollo-link-http'
import {
    ApolloLink
} from 'apollo-link';
import authMiddleware from "./apolloConfig/apolloMiddleware";
import errorLink from "./apolloConfig/apolloErrors";
import {
    cache,
    defaultOptions
} from "./apolloConfig/apolloCache"

const httpLink = createHttpLink({
    uri: process.env.MIX_APP_GRAPHQL_API,
    credentials: 'include',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    // Make all queries to API with GET method - helpful with PWA caching
    useGETForQueries: true,
})


const link = ApolloLink.from([
    errorLink,
    authMiddleware,
    httpLink,
]);

// Create the apollo client
const apolloClient = new ApolloClient({
    credentials: 'include',
    link,
    cache,
    defaultHttpLink: false,
    connectToDevTools: false,
    defaultOptions
})

export default apolloClient;
