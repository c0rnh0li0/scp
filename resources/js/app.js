//import 'babel-polyfill'

require('./bootstrap');

window.Vue = require('vue');

Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

import vuetify from './plugins/vuetify';
import router from './router/index';
import store from './store/index';
import './plugins/google-maps'
import './plugins/tiptap-vuetify'


axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrf;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.localStorage.getItem('token');
axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response.status == 404 || error.response.status == 401)
        router.push({ path: '/' + error.response.status })
    return Promise.reject(error.response);
});

import './registerServiceWorker'

Vue.component('index', require('./components/Index.vue').default);

import './../sass/main.scss'

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: vuetify,
    render: h => h('index'),
    async beforeCreate() {
        await this.$store.dispatch('getSession');
        await this.$store.dispatch('getLookups');
    },
    async created() {

    },
    async mounted() {

    }
});
