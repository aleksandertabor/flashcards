import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/dist/vuetify.min.css'
import Vuetify from 'vuetify/lib'

const opts = {
    icons: {
        iconfont: 'mdi',
    },
    theme: {
        dark: false
    }
}

const vuetify = new Vuetify({
    opts
});

export default vuetify;
