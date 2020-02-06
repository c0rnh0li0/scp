//import 'babel-polyfill'

require('./bootstrap');

window.Vue = require('vue');

Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

import Vuetify from 'vuetify';
import router from './router/index';
import store from './store/index';


Vue.component('index', require('./components/Index.vue').default);

Vue.use(Vuetify);

import './../sass/main.scss'

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: new Vuetify(),
    render: h => h('index'),
});
