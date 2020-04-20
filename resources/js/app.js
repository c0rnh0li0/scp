require('./bootstrap');

import './messaging'
import './plugins/install'

window.Vue = require('vue');

Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

import './config';
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

import Home from './components/Home'
import Index from './components/Index'
import Loading from './components/Loading'

import './../sass/main.scss'

import { mapState } from 'vuex'

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: vuetify,
    components: {
        Home,
        Index,
        Loading
    },
    watch: {
        session(newVal, oldVal) {
            if (newVal.user) {
                this.inSession = true
                this.$router.push(this.$store.state.endpoint)
            }
            return newVal
        },
    },
    computed:
        mapState({
            session: state => state.session
        })
    ,
    data: () => ({
        inSession: false,
        loading: false
    }),
    methods: {
        async login(endpoint, data) {
            this.loading = true
            let that = this
            $.post(endpoint, data)
                .done(async function(response, msg, jqx) {
                    $('.featherlight-close').click()

                    if (response.success) {
                        window.localStorage.setItem('token', response.success.token);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.success.token;

                        await that.$store.dispatch('getSession')
                        await that.$store.dispatch('getLookups')
                    }
                    else {
                        alert('no login success')
                    }
                })
                .fail(function(jqx, error, msg) {
                    console.log('login fail', arguments);
                })
                .always(function() {
                    that.loading = false
                });
        },
        async logout() {
            this.loading = true
            let that = this
            axios.post(window.Laravel.logoutUrl, {})
                .then((response) => {
                    if (response.data.response.success) {
                        that.$store.dispatch('destroySession');
                        window.localStorage.removeItem('token')
                        that.inSession = false
                        that.$router.push('/')
                    }

                    that.loading = false
                })
                .catch((err) => {
                    console.log('logout', err);
                    that.loading = false
                });
        },
        register(endpoint, data) {
            $.post(endpoint, data )
                .done(async function(response, msg, jqx) {
                    /*if (response.success) {
                        window.localStorage.setItem('token', response.success.token);

                        await this.$store.dispatch('getSession')
                        await this.$store.dispatch('getLookups')

                    }
                    else {}*/
                })
                .fail(function(jqx, error, msg) {
                    //$('.error-container').show();

                    console.log('register fail', arguments);
                })
                .always(function() {
                    // console.log('login always', arguments);
                    //
                });
        }
    },
    //render: h => h('home'),
    async beforeCreate() {
        await this.$store.dispatch('getSession')
        await this.$store.dispatch('getLookups')
    },
    async created() {

    },
    async mounted() {

    }
});