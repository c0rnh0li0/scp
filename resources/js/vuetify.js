import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

import en from 'vuetify/es5/locale/en'
import mk from './i18n/vuetify/mk'

export default new Vuetify({
    iconfont: "mdi",
    lang: {
        locales: { en, mk },
        // If you change the language here, then it changes in the editor itself
        current: 'mk' // en | es | fr | pl | ru
    }
})