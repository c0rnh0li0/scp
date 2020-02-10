//import 'babel-polyfill'

require('./bootstrap');

window.Vue = require('vue');

require('./tokens')
require('./axios')

import Vuetify from 'vuetify';
import store from './store/index';
import router from './router/index';

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
