import apolloClient from "@/httpClients/apollo/apolloClient";
import {
    DECK,
    DECKS
} from "@/queries/decks.gql";

export default {
    async decks(context, payload) {
        try {
            const response = (await apolloClient.query({
                query: DECKS,
                variables: {
                    amount: payload.amount,
                    page: payload.page,
                    query: payload.query,
                    filter: payload.filter,
                }
            })).data.decks;

            if (response.data.length) {
                context.commit('decks', response.data);
            }

            return Promise.resolve(response)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async deck({}, payload) {
        try {
            const deck = (await apolloClient.query({
                query: DECK,
                variables: {
                    slug: payload
                }
            })).data.deck;

            return Promise.resolve(deck)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
}
