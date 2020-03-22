import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        sessionEndpoint: '/api/auth',
        session: {},
        username: '',
        email: '',
        types: {
            type_1: 'admin',
            type_2: 'tourist',
            type_3: 'place',
        },
        type: null,
        menus: {
            admin: [
                { icon: 'mdi-home-modern', text: 'Home', route: '/admin' },
                { icon: 'mdi-file-document-edit', text: 'Contracts', route: '/admin/contracts' },
                { icon: 'mdi-bank', text: 'Places', route: '/admin/places' },
                { icon: 'mdi-account', text: 'People', route: '/admin/people' },
                { icon: 'mdi-qrcode-scan', text: 'Tickets', route: '/admin/tickets' },
                { divider: true },
                { icon: 'mdi-database', text: 'Lookup data', route: '/admin/lookups' },
                { icon: 'mdi-certificate-outline', text: 'Tokens', route: '/admin/tokens' },
                { icon: 'mdi-information-outline', text: 'About', route: '/about' },
            ],
            place: [
                { icon: 'mdi-qrcode-scan', text: 'Scan', route: '/place' },
                { icon: 'mdi-home-modern', text: 'Home', route: '/place/statistics' },
                { icon: 'mdi-account', text: 'Profile', route: '/place/profile' },
                { icon: 'mdi-book-plus', text: 'Offers', route: '/place/offers' },
            ],
        },
        menu: [],
        quicks: {
            admin: [],
            place: [
                { icon: 'mdi-account', text: 'Profile', route: '/place/profile' },
                { icon: 'mdi-qrcode-scan', text: 'Scan', route: '/place' },
            ],
        },
        quick: [],
        lookups: {
            categories: [],
            cities: [],
            gengers: []
        },
        google_api_key: 'AIzaSyDrKxNprd3CrfaSzH_u__tPzpUNd-aRpmU'
    },
    mutations: {
          setSession (state, payload) {
              state.type = state.types['type_' + payload.type.id]
              state.menu = state.menus[state.type]
              state.quick = state.quicks[state.type]
              state.username = payload.user.name
              state.email = payload.user.email
              state.session = payload
          },
          setLookups (state, payload) {
              state.lookups.categories = payload.categories
              state.lookups.cities = payload.cities
              state.lookups.gengers = payload.genders
          },
    },
    actions: {
          async getSession({commit}) {
              return await axios.post(this.state.sessionEndpoint).then((response) => {
                  commit('setSession', response.data.data)
              });
          },
          async getLookupData({commit}) {
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
        username: (state) => {
            return state.username
        },
        email: (state) => {
            return state.email
        },
        quick: (state) => {
            return state.quick
        },
        google: (state) => {
            return state.google_api_key
        }
    }
})
