export const validationErrors = function (errors) {
    const {
        graphQLErrors: {
            validationErrors
        }
    } = errors;

    return validationErrors;
};

export const errorMessage = function (errors) {
    const {
        graphQLErrors: {
            0: {
                message
            }
        }
    } = errors;

    return message;
}
