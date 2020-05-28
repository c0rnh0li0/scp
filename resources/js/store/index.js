import Vue from 'vue'
import Vuex from 'vuex'
import i18n from './../translations'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        sessionEndpoint: '/api/auth',
        settingsEndpoint: '/api/settings',
        session: {},
        settings: {},
        id: -1,
        username: '',
        email: '',
        picture: 'avatar_default.png',
        valute: '',
        types: {
            type_1: 'admin',
            type_2: 'tourist',
            type_3: 'place',
        },
        type: null,
        endpoints: {
            type_1: '/admin',
            type_2: '/home',
            type_3: '/place',
        },
        endpoint: null,
        menus: {
            admin: [
                { divider: true },
                { icon: 'mdi-home-modern', text: 'message.menus.admin.menu.items.home', route: '/admin/dashboard' },
                { icon: 'mdi-cogs', text: 'message.menus.admin.menu.items.settings', route: '/admin/settings' },
                {
                    icon: 'mdi-database', text: 'message.menus.admin.menu.items.lookups', route: '/admin/lookups', active: false,
                    items: [
                        { text: 'message.menus.admin.menu.items.lookup_items.contract_lengths', route: '/admin/lookups/contractlengths' },
                        { text: 'message.menus.admin.menu.items.lookup_items.languages', route: '/admin/lookups/languages' },
                        { text: 'message.menus.admin.menu.items.lookup_items.user_types', route: '/admin/lookups/usertypes' },
                        { text: 'message.menus.admin.menu.items.lookup_items.categories', route: '/admin/lookups/categories' },
                        { text: 'message.menus.admin.menu.items.lookup_items.countries', route: '/admin/lookups/countries' },
                        { text: 'message.menus.admin.menu.items.lookup_items.cities', route: '/admin/lookups/cities' },
                        { text: 'message.menus.admin.menu.items.lookup_items.genders', route: '/admin/lookups/genders' },
                        { text: 'message.menus.admin.menu.items.lookup_items.valutes', route: '/admin/lookups/valutes' },
                    ]
                },
                { divider: true },
                { icon: 'mdi-file-document-edit', text: 'message.menus.admin.menu.items.contracts', route: '/admin/contracts' },
                { icon: 'mdi-bank', text: 'message.menus.admin.menu.items.places', route: '/admin/places' },
                { icon: 'mdi-account', text: 'message.menus.admin.menu.items.people', route: '/admin/people' },
                { icon: 'mdi-book-plus', text: 'message.menus.admin.menu.items.offers', route: '/admin/offers' },
                { icon: 'mdi-qrcode-scan', text: 'message.menus.admin.menu.items.tickets', route: '/admin/tickets' },
                { icon: 'mdi-blogger', text: 'message.menus.admin.menu.items.blog', route: '/admin/blog' },
                { divider: true },
                { icon: 'mdi-certificate-outline', text: 'message.menus.admin.menu.items.tokens', route: '/admin/tokens' },
                { divider: true },
                { icon: 'mdi-information-outline', text: 'message.menus.admin.menu.items.about', route: '/about' },
            ],
            place: [
                { divider: true },
                { icon: 'mdi-home-modern', text: 'message.menus.place.menu.items.home', route: '/place/dashboard' },
                { icon: 'mdi-qrcode-scan', text: 'message.menus.place.menu.items.scan', route: '/place/scan' },
                { icon: 'mdi-account', text: 'message.menus.place.menu.items.profile', route: '/place/profile' },
                { icon: 'mdi-book-plus', text: 'message.menus.place.menu.items.offers', route: '/place/offers' },
                { icon: 'mdi-account-circle', name: 'business_profile', text: 'message.menus.place.menu.items.profile_preview', route: '/place/business/' },
                { divider: true },
                { icon: 'mdi-information-outline', text: 'message.menus.place.menu.items.about', route: '/about' },
            ],
            tourist: [
                { divider: true },
                { icon: 'mdi-home-modern', text: 'Dashboard', route: '/home/dashboard' },
                { icon: 'mdi-map-marker', text: 'Map', route: '/home/map' },
                { icon: 'mdi-book-plus', text: 'Offers', route: '/home/offers' },
                { icon: 'mdi-qrcode-scan', text: 'Tickets', route: '/home/tickets' },
                { icon: 'mdi-account', text: 'Profile', route: '/home/profile' },
                { divider: true },
                { icon: 'mdi-information-outline', text: 'About', route: '/about' },
            ],
        },
        menu: [],
        quicks: {
            admin: [],
            place: [
                { icon: 'mdi-qrcode-scan', text: 'Scan', route: '/place/scan' },
            ],
            tourist: [
                { icon: 'mdi-map-marker', text: 'Map', route: '/home/map' },
            ]
        },
        quick: [],
        lookups: {
            categories: [],
            cities: [],
            genders: [],
            valutes: [],
            countries: []
        },
        google_api_key: 'AIzaSyDrKxNprd3CrfaSzH_u__tPzpUNd-aRpmU',
        avatars_path: '/storage/avatars/',
        promo_images_path: '/storage/promo_images/',
        tickets_path: '/storage/tickets/',
        logo_path: '/storage/logo/',
        flags_path: '/storage/flags/',
    },
    mutations: {
        setSettings (state, payload) {
            state.settings = payload
        },
        triggerSession (state, payload) {
            state.session.user = {}
        },
        setSession (state, payload) {
            state.type = state.types['type_' + payload.type.id]
            state.endpoint = state.endpoints['type_' + payload.type.id]
            state.menu = state.menus[state.type]
            state.quick = state.quicks[state.type]
            state.id = payload.user.id
            state.username = payload.user.name
            state.email = payload.user.email
            state.picture = payload.picture ? payload.picture : state.picture
            state.valute = payload.valute ? payload.valute.id : ''
            state.session = payload
        },
        destroySession(state) {
            state.type = ''
            state.endpoint = ''
            state.menu = []
            state.quick = []
            state.id = -1
            state.username = ''
            state.email = ''
            state.picture = ''
            state.valute = ''
            state.session = {}
        },
        setLookups (state, payload) {
            state.lookups.categories = payload.categories
            state.lookups.cities = payload.cities
            state.lookups.genders = payload.genders
            state.lookups.valutes = payload.valutes
            state.lookups.countries = payload.countries
        },
    },
    actions: {
        async getSettings({commit}) {
            return await axios.get(this.state.settingsEndpoint).then((response) => {
                commit('setSettings', response.data.data)
            });
        },
        updateSettings({commit}, payload) {
            commit('setSettings', payload)
        },
        async getSession({commit}) {
            return await axios.post(this.state.sessionEndpoint).then((response) => {
                commit('setSession', response.data.data)
            });
        },
        setOfflineSession({commit}, payload) {
            commit('setSession', payload)
        },
        destroySession({commit}) {
            commit('destroySession')
        },
        triggerSession({commit}) {
            commit('triggerSession')
        },
        async getLookups({commit}) {
            return await axios.get('/api/lookups').then((response) => {
                commit('setLookups', response.data)
            });
        }
    },
    modules: {},
    getters: {
        session: async (state) => {
            return state.session
        },
        settings: async (state) => {
            return state.settings
        },
        menu: (state, type) => {
            return state.menu
        },
        type: (state) => {
            return state.type
        },
        id: (state) => {
            return state.id
        },
        endpoint: (state) => {
            return state.endpoint
        },
        username: (state) => {
            return state.username
        },
        email: (state) => {
            return state.email
        },
        picture: (state) => {
            return state.picture
        },
        valute: (state) => {
            return state.valute
        },
        quick: (state) => {
            return state.quick
        },
        google: (state) => {
            return state.google_api_key
        },
        lookups: (state) => {
            return state.lookups
        },
        avatars_path: (state) => {
            return state.avatars_path
        },
        promo_images_path: (state) => {
            return state.promo_images_path
        },
        tickets_path: (state) => {
            return state.tickets_path
        },
        logo_path: (state) => {
            return state.logo_path
        },
        flags_path: (state) => {
            return state.flags_path
        },
    }
})
