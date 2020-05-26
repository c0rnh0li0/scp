import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const messages = {
    en: require('./i18n/en').default,
    mk: require('./i18n/mk').default
}

const i18n = new VueI18n({
    locale: process.env.VUE_APP_I18N_LOCALE || 'mk',
    fallbackLocale: process.env.VUE_APP_I18N_FALLBACK_LOCALE || 'en',
    messages
})

Vue.prototype.$locale = {
    change (language) {
        i18n.locale = language;
    },
    current () {
        return i18n.locale;
    }
};

export default i18n