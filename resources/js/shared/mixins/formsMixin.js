export default {
    data() {
        return {
            errors: null,
            error: null,
            loading: false,
            success: false,
            rules: {
                required: v => !!v || "Required.",
                min: v => (v && v.length) >= 6 || "Min 6 characters",
                max: len => v =>
                    !v || (v && v.length <= len) || "Max " + len + " characters",
                email: v => /.+@.+\..+/.test(v) || "E-mail must be valid",
                size: value =>
                    !value ||
                    value.size < 2097152 ||
                    "File size should be less than 2 MB!",
                length: len => v =>
                    (v || "").length >= len ||
                    "Invalid character length, required " + len,
                url: v =>
                    !v ||
                    /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi.test(
                        v
                    ) ||
                    "Wrong URL.",
            }
        };
    },
    methods: {
        errorFor(field) {
            return this.hasErrors && this.errors[field] ? this.errors[field] : null;
        },
        resetState() {
            this.loading = true;
            this.errors = null;
            this.error = false;
            this.success = false;
        },
    },
    computed: {
        hasErrors() {
            return this.errors !== null;
        }
    }
};
