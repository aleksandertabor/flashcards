import {
    getXsrfToken
} from "@/shared/utils/cookies"
import {
    setContext
} from 'apollo-link-context';

const authMiddleware = setContext(async (req, {
    headers
}) => {
    const xsrfToken = await getXsrfToken();

    return {
        headers: {
            ...headers,
            'X-XSRF-TOKEN': xsrfToken ? xsrfToken : ''
        },
    };
});

export default authMiddleware;
