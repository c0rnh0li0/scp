//import 'babel-polyfill'

var deferredPrompt;
window.addEventListener('beforeinstallprompt', function(event) {
    event.preventDefault();
    deferredPrompt = event;
    return false;
});

function addToHomeScreen() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(function (choiceResult) {
            console.log(choiceResult.outcome);
            if (choiceResult.outcome === 'dismissed') {
                console.log('User cancelled installation');
            } else {
                console.log('User added to home screen');
            }
        });
        deferredPrompt = null;
    }
}

require('./bootstrap');


window.Vue = require('vue');

Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

import './messaging'
import router from './router/index';
import store from './store/index';
import vuetify from './plugins/vuetify';
import './plugins/google-maps'
import './plugins/tiptap-vuetify'

require('../homepage/js/all');

axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrf;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.localStorage.getItem('token');
axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response.status == 404)
        router.push({ path: '/' + error.response.status })
    else if (error.response.status == 401 && window.location.pathname != '/')
        router.push({ path: '/' })
    return Promise.reject(error.response);
});

Vue.module.exports = {
    runtimeCompiler: true
};

Vue.component('Index', require('./components/Index.vue').default);
Vue.component('Home', require('./components/Home.vue').default);

import './../sass/main.scss'

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: vuetify,
    computed: {
        inSession() {
            return this.$store.state.session.user
        },
    },
    watch: {
        inSession(newVal, oldVal) {
            return newVal
        },
    },
    data: () => ({
        //inSession: null
    }),
    //render: h => h('home'),
    async beforeCreate() {
        let allowedPages = [
            '/',
            '/offline',
            '/about',
            '/error',
            '/404'
        ]

        if (allowedPages.indexOf(window.location.pathname) < 0) {
            await this.$store.dispatch('getSession');
            await this.$store.dispatch('getLookups');
        }
    },
    async created() {

    },
    async mounted() {

    }
});