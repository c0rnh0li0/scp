import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const translations = {
    en: require('./i18n/en').default,
    mk: require('./i18n/mk').default
}

export default new VueI18n({
    locale: 'mk',
    translations
})