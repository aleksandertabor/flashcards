import {
    InMemoryCache
} from 'apollo-cache-inmemory'

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
        errorPolicy: 'none',
    },
}

export {
    cache,
    defaultOptions
}
