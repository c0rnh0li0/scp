//import 'babel-polyfill'

require('./bootstrap');

window.Vue = require('vue');
import Vuetify from 'vuetify';
import router from './router/index';


Vue.component('index', require('./components/Index.vue').default);

Vue.use(Vuetify);

import './../sass/main.scss'

const app = new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify(),
    render: h => h('index'),
});
