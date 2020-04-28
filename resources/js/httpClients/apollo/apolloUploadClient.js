import {
    ApolloClient
} from 'apollo-client'
import {
    createUploadLink
} from "apollo-upload-client";
import {
    ApolloLink
} from 'apollo-link';
import authMiddleware from "./apolloConfig/apolloMiddleware";
import errorLink from "./apolloConfig/apolloErrors";
import {
    cache,
    defaultOptions
} from "./apolloConfig/apolloCache"

const uploadLink = createUploadLink({
    uri: process.env.MIX_APP_GRAPHQL_API,
    credentials: 'include',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
});


const link = ApolloLink.from([
    errorLink,
    authMiddleware,
    uploadLink,
]);


// Create the apollo upload client
const apolloUploadClient = new ApolloClient({
    credentials: 'include',
    link,
    cache,
    defaultHttpLink: false,
    connectToDevTools: false,
    defaultOptions
})

export default apolloUploadClient;
