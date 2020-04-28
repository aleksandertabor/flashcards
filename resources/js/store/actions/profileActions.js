import apolloClient from "@/httpClients/apollo/apolloClient";
import apolloUploadClient from "@/httpClients/apollo/apolloUploadClient";
import {
    PROFILE,
    EDIT_PROFILE,
    CHANGE_AVATAR,
    REMOVE_PROFILE
} from "@/queries/profile.gql";

export default {
    async profile({}, payload) {
        try {
            const profile = (await apolloClient.query({
                query: PROFILE,
                variables: {
                    username: payload,
                }
            })).data.profile;

            return Promise.resolve(profile)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async editProfile(context, payload) {
        try {
            const user = (await apolloClient.mutate({
                mutation: EDIT_PROFILE,
                variables: {
                    data: payload
                }
            })).data.editProfile;

            context.dispatch("auth/setUser", user, {
                root: true
            })

            return Promise.resolve(user)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async changeAvatar(context, payload) {
        try {
            const user = (await apolloUploadClient.mutate({
                mutation: CHANGE_AVATAR,
                variables: {
                    data: payload
                }
            })).data.changeAvatar;

            context.dispatch("auth/setUser", user, {
                root: true
            })

            return Promise.resolve(user)
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
    async removeProfile(context, payload) {
        try {
            await apolloClient.mutate({
                mutation: REMOVE_PROFILE,
                variables: {
                    id: payload
                }
            })

            context.dispatch("auth/forceLogout", null, {
                root: true
            })
        } catch (errors) {
            return Promise.reject(errors)
        }
    },
};
