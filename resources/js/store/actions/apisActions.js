import apolloClient from "@/httpClients/apollo/apolloClient";
import {
    TRANSLATE,
    IMAGE,
    EXAMPLE
} from "@/queries/apis.gql";

export default {
    async translate({}, payload) {
        try {
            const translate = (await apolloClient.query({
                query: TRANSLATE,
                variables: {
                    input: payload,
                }
            })).data.translate;

            return Promise.resolve(translate)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async image({}, payload) {
        try {
            const image = (await apolloClient.query({
                query: IMAGE,
                variables: {
                    input: payload,
                }
            })).data.image;

            return Promise.resolve(image)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async example({}, payload) {
        try {
            const example = (await apolloClient.query({
                query: EXAMPLE,
                variables: {
                    input: payload,
                }
            })).data.example;

            return Promise.resolve(example)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
}
