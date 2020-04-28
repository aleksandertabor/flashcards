export default {
    data() {
        return {
            installBtnText: "Install"
        };
    },
    methods: {
        install() {
            if (installPromptGlobal !== null) {
                installPromptGlobal.prompt();
                installPromptGlobal.userChoice.then(choiceResult => {
                    if (choiceResult.outcome === "accepted") {
                        console.log("User accepted the A2HS prompt");
                    } else {
                        console.log("User dismissed the A2HS prompt");
                    }
                    installPromptGlobal = null;
                });
            }
        }
    },
    created() {
        window.addEventListener(
            "appinstalled",
            function (e) {
                this.installBtnText = "Installed";
                console.log("App installed.");
            }.bind(this)
        );
    },
};
