export default {
    data() {
        return {
            imageRenderKey: 0,
        };
    },
    methods: {
        forceImageRerender() {
            this.imageRenderKey += 1;
        },
        previewImage(fileEvent, model) {
            if (fileEvent) {
                let filename = fileEvent.name;
                if (filename.lastIndexOf(".") <= 0) {
                    return;
                }
                const fileReader = new FileReader();
                fileReader.readAsDataURL(fileEvent);
                fileReader.addEventListener("load", () => {
                    this[model].image = fileReader.result;
                    this.forceImageRerender();
                });
                this[model].image_file = fileEvent;
            } else {
                this[model].image_file = null;
                this[model].image = "";
            }
        },
    },
};
