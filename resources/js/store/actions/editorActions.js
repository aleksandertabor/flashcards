import apolloClient from "@/httpClients/apollo/apolloClient";
import apolloUploadClient from "@/httpClients/apollo/apolloUploadClient";
import {
    DECK_TO_EDIT,
    DECK_EDITOR,
    CREATE_DECK,
    UPDATE_DECK,
    REMOVE_DECK,
    CREATE_CARD,
    UPDATE_CARD,
    REMOVE_CARD,
} from "@/queries/editor.gql";

export default {
    async deckToEdit({}, payload) {
        try {
            const deckToEdit = (await apolloClient.query({
                query: DECK_TO_EDIT,
                variables: {
                    slug: payload,
                }
            })).data.deck;

            return Promise.resolve(deckToEdit)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async deckEditor() {
        try {
            const deckEditor = (await apolloClient.query({
                query: DECK_EDITOR,
            })).data;

            return Promise.resolve(deckEditor)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async createDeck({}, payload) {
        try {
            const createDeck = (await apolloUploadClient.mutate({
                mutation: CREATE_DECK,
                variables: {
                    input: {
                        title: payload.title,
                        description: payload.description,
                        image: payload.image,
                        image_file: payload.image_file,
                        lang_source_id: payload.lang_source_id,
                        lang_target_id: payload.lang_target_id,
                        visibility: payload.visibility.value,
                    }
                }
            })).data.createDeck;

            return Promise.resolve(createDeck)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async updateDeck({}, payload) {
        try {
            const updateDeck = (await apolloUploadClient.mutate({
                mutation: UPDATE_DECK,
                variables: {
                    input: {
                        id: payload.id,
                        title: payload.title,
                        description: payload.description,
                        image: payload.image,
                        image_file: payload.image_file,
                        lang_source_id: payload.lang_source_id,
                        lang_target_id: payload.lang_target_id,
                        visibility: payload.visibility.value,
                    }
                }
            })).data.updateDeck;

            return Promise.resolve(updateDeck)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async removeDeck({}, payload) {
        try {
            const removeDeck = (await apolloClient.mutate({
                mutation: REMOVE_DECK,
                variables: {
                    id: payload
                }
            })).data.removeDeck;

            return Promise.resolve(removeDeck)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async createCard({}, payload) {
        try {
            const createCard = (await apolloUploadClient.mutate({
                mutation: CREATE_CARD,
                variables: {
                    input: {
                        deck_id: payload.deck_id,
                        question: payload.question,
                        answer: payload.answer,
                        image: payload.image,
                        image_file: payload.image_file,
                        example_question: payload.example_question,
                        example_answer: payload.example_answer,
                    }
                }
            })).data.createCard;

            return Promise.resolve(createCard)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async updateCard({}, payload) {
        try {
            const updateCard = (await apolloUploadClient.mutate({
                mutation: UPDATE_CARD,
                variables: {
                    input: {
                        id: payload.id,
                        deck_id: payload.deck_id,
                        question: payload.question,
                        answer: payload.answer,
                        image: payload.image,
                        image_file: payload.image_file,
                        example_question: payload.example_question,
                        example_answer: payload.example_answer,
                    }
                }
            })).data.updateCard;

            return Promise.resolve(updateCard)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async removeCard({}, payload) {
        try {
            const removeCard = (await apolloClient.mutate({
                mutation: REMOVE_CARD,
                variables: {
                    id: payload
                }
            })).data.removeCard;

            return Promise.resolve(removeCard)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
}
