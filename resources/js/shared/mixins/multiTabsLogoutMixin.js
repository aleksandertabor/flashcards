export default {
    methods: {
        onLocalStorageUpdate(event) {
            // if user logout on any browser tab
            if (event.key === "user") {
                if (event.newValue === null) {
                    this.$store.dispatch("auth/forceLogout");
                    this.$router.push({
                        name: "login"
                    });
                }
            }
        },
    },
    mounted() {
        window.addEventListener("storage", this.onLocalStorageUpdate);
    },
    beforeDestroy() {
        window.removeEventListener('storage', this.onLocalStorageUpdate)
    },
};
