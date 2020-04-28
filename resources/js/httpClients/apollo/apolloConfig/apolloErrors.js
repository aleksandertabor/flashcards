import {
    onError
} from "apollo-link-error";
import store from "@/config/store";
import router from '@/config/routes';

const errorLink = onError(({
    graphQLErrors,
    networkError,
}) => {
    if (graphQLErrors) {
        for (let err of graphQLErrors) {
            switch (err.extensions.category) {
                case 'internal':
                    alert("Something went wrong.")
                    break;
                case 'authentication':
                    console.log("No authenticated.");
                    store.dispatch("auth/forceLogout")
                    router.push({
                        name: "login"
                    });
                    break;
                case 'validation':
                    graphQLErrors.validationErrors = err.extensions.validation;
                    break;
            }
        }
    }
    if (networkError && (networkError.statusCode === 401 || networkError.statusCode === 419 || networkError.statusCode === 503)) {
        store.dispatch("auth/forceLogout")
        router.push({
            name: "login"
        });
    }
});

export default errorLink;
