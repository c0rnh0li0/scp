import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        sessionEndpoint: '/api/auth',
        session: {},
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
        menus: {
            admin: [
                { divider: true },
                { icon: 'mdi-home-modern', text: 'Home', route: '/admin/dashboard' },
                { icon: 'mdi-file-document-edit', text: 'Contracts', route: '/admin/contracts' },
                { icon: 'mdi-bank', text: 'Places', route: '/admin/places' },
                { icon: 'mdi-account', text: 'People', route: '/admin/people' },
                { icon: 'mdi-qrcode-scan', text: 'Tickets', route: '/admin/tickets' },
                { divider: true },
                { icon: 'mdi-database', text: 'Lookup data', route: '/admin/lookups' },
                { icon: 'mdi-certificate-outline', text: 'Tokens', route: '/admin/tokens' },
                { divider: true },
                { icon: 'mdi-information-outline', text: 'About', route: '/about' },
            ],
            place: [
                { divider: true },
                { icon: 'mdi-home-modern', text: 'Dashboard', route: '/place/statistics' },
                { icon: 'mdi-qrcode-scan', text: 'Scan', route: '/place/scan' },
                { icon: 'mdi-account', text: 'Profile', route: '/place/profile' },
                { icon: 'mdi-book-plus', text: 'Offers', route: '/place/offers' },
                { icon: 'mdi-account-circle', name: 'business_profile', text: 'Profile Preview', route: '/place/business/' },
                { divider: true },
                { icon: 'mdi-information-outline', text: 'About', route: '/about' },
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
    },
    mutations: {
          setSession (state, payload) {
              state.type = state.types['type_' + payload.type.id]
              state.menu = state.menus[state.type]
              state.quick = state.quicks[state.type]
              state.id = payload.user.id
              state.username = payload.user.name
              state.email = payload.user.email
              state.picture = payload.picture ? payload.picture : state.picture
              state.valute = payload.valute ? payload.valute.id : ''
              state.session = payload
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
          async getSession({commit}) {
              return await axios.post(this.state.sessionEndpoint).then((response) => {
                  commit('setSession', response.data.data)
              });
          },
          async getLookups({commit}) {
              return await axios.post('/api/lookups').then((response) => {
                  commit('setLookups', response.data)
              });
          }
    },
    modules: {},
    getters: {
        session: async (state) => {
            return state.session
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
        }
    }
})
