import LazyLoad from "vanilla-lazyload";

const lazyLoadInstance = new LazyLoad({
    elements_selector: '.lazy',
});

// export default lazyLoadInstance;

export default {
    install: function (Vue, name = '$LazyLoad') {
        Object.defineProperty(Vue.prototype, name, {
            value: lazyLoadInstance
        });
        Vue.mixin({
            mounted() {
                //? maybe add if image url exists on component
                this.$LazyLoad.update();
            }
        })
    }
}
