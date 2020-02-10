//import 'babel-polyfill'

require('./bootstrap');

window.Vue = require('vue');

Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

import Vuetify from 'vuetify';
import router from './router/index';
import store from './store/index';

axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrf;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.localStorage.getItem('token');
axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    console.log('global axios error', arguments)
    router.push({ path: '/' + response[0].response.status })
    //return Promise.reject(error);
});

Vue.component('index', require('./components/Index.vue').default);

Vue.use(Vuetify);

import './../sass/main.scss'

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: new Vuetify(),
    render: h => h('index'),
    created() {
        this.$store.dispatch('getSession');
    },
    mounted() {

    }
});
