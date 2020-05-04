require('./bootstrap');

//import './messaging'

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

axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrf;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + atob(window.localStorage.getItem('_t'));
axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response.status == 404)
        router.push({ path: '/' + error.response.status })
    else if (error.response.status == 401 && window.location.pathname != '/')
        router.push({ path: '/' })
    return Promise.reject(error.response);
});

import VueCrypt from 'vue-crypt'
import VOffline from 'v-offline';
import InstallBanner from './components/InstallBanner';

Vue.use(VueCrypt)
Vue.use(VOffline)

import Home from './components/Home'
import Index from './components/Index'
import Loading from './components/Loading'

import { mapState } from 'vuex'

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: vuetify,
    components: {
        Home,
        Index,
        Loading,
        VOffline,
        InstallBanner
    },
    watch: {
        session(newVal, oldVal) {
            if (newVal.user) {
                this._s_set('_s', JSON.stringify(newVal))

                this.inSession = true
                if (window.location.pathname == '/')
                    this.$router.push(this.$store.state.endpoint)
            }

            this.loading = false
            return newVal
        },
        loading(newVal, oldVal) {
            return newVal
        }
    },
    computed:
        mapState({
            session: state => state.session,
            settings: state => state.settings
        })
    ,
    data: () => ({
        inSession: false,
        loading: true,
        isOnline: true,
        isFreshLogin: false,
        loginErrors: [],
        registerErrors: [],
        loginDialog: false,
        registerDialog: false
    }),
    methods: {
        async checkOnlineStatus(e) {
            this.isOnline = e;
            await this.start()
        },
        async start() {
            this.loading = true
            let that = this

            if (this.isOnline) {
                this.$store.dispatch('getSettings')
                    .then(resp => {
                        this.$store.dispatch('getSession')
                            .then(resp => {
                                that.loading = false
                                that.$store.dispatch('getLookups')
                            })
                            .catch(err => {
                                that.loading = false
                            })
                    })
                    .catch(err => {
                        that.loading = false
                    })
            }
            else {
                if (!this.$store.state.session.user) {
                    let _s = this._s_get('_s')
                    if (_s)
                        this.$store.dispatch('setOfflineSession', _s)
                    else {
                        this.$router.push('/offline')
                    }

                    this.loading = false
                }
            }
        },
        actions(route) {
            if (route == 'login') {
                this.loginDialog = true
            }
            else if (route == 'register') {
                this.registerDialog = true
            }
        },
        registerclose() {
            this.registerDialog = false
        },
        loginclose() {
            this.loginDialog = false
        },
        login(endpoint, data) {
            this.loading = true

            if (this.isOnline) {
                let that = this
                axios.post(endpoint, data)
                    .then(response => {
                        if (response.data.success) {
                            window.localStorage.setItem('_t', btoa(response.data.success.token))
                            axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.success.token
                            that.isFreshLogin = true

                            that.$store.dispatch('getSession')
                                .then(response => {
                                    that.$store.dispatch('getLookups')
                                })
                        }
                        else {
                            alert('no login success')
                        }

                        that.loginDialog = false
                    })
                    .catch(error => {
                        that.loginErrors = error.data.errors
                        that.loading = false
                    });
            }
            else {
                console.log('offline, what to do with login???')
                this.loading = false
            }
        },
        async logout() {
            this.loading = true

            if (this.isOnline) {
                let that = this
                axios.post(window.Laravel.logoutUrl, {})
                    .then((response) => {
                        if (response.data.response.success) {
                            that.$store.dispatch('destroySession');
                            window.localStorage.removeItem('_t')
                            window.localStorage.removeItem('_s')
                            that.inSession = false
                            that.$router.push('/')
                        }

                        that.loading = false
                    })
                    .catch((err) => {
                        console.log('logout', err);
                        that.loading = false
                    });
            }
            else {
                console.log('do we need to logout if offline?')
                /*
                this.$store.dispatch('destroySession');
                window.localStorage.removeItem('_t')
                window.localStorage.removeItem('_s')
                this.inSession = false
                this.$router.push('/')
                this.loading = false
                */
            }
        },
        async register(endpoint, data) {
            let that = this
            axios.post(endpoint, data)
                .then(async (response) => {
                    console.log('register response', response)
                    if (response.data.success) {
                        // TODO: Probably send a confirmation email, etc...
                        //window.localStorage.setItem('token', response.data.success.token);

                        //await that.$store.dispatch('getSession')
                        //await that.$store.dispatch('getLookups')

                        that.registerDialog = false
                    }
                    else {}
                })
                .catch(error => {
                    that.registerErrors = error.data.error
                    that.loading = false
                });
        },
        _s_set(key, val) {
            this.$aes.setKey(atob(window.localStorage.getItem('_t')))
            window.localStorage.setItem(key, this.$aes.encrypt(val))
        },
        _s_get(key) {
            this.$aes.setKey(atob(window.localStorage.getItem('_t')))
            let val = window.localStorage.getItem(key)
            if (val) {
                let decr = this.$aes.decrypt(val)
                return JSON.parse(decr)
            }

            return null
        }
    },
    async beforeCreate() {

    },
    async created() {
        //await this.start()
    },
    async mounted() {

    }
});