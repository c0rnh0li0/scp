import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        sessionEndpoint: '/api/auth',
        session: {},
        menus: {
            admin: [
                { icon: 'mdi-home-modern', text: 'Home', route: '/admin/dashboard' },
                { icon: 'mdi-file-document-edit', text: 'Contracts', route: '/admin/contracts' },
                { icon: 'mdi-bank', text: 'Places', route: '/admin/places' },
                { icon: 'mdi-account', text: 'People', route: '/admin/people' },
                { icon: 'mdi-qrcode-scan', text: 'Tickets', route: '/admin/tickets' },
                { divider: true },
                { icon: 'mdi-database', text: 'Lookup data', route: '/admin/lookups' },
                { icon: 'mdi-certificate-outline', text: 'Tokens', route: '/admin/tokens' },
                { icon: 'mdi-information-outline', text: 'About', route: '/about' },
            ],
            tourist: [],
            place: []
        },
        userTypes: {
            'Administrator': 'admin',
            'Tourist': 'tourist',
            'Place': 'place'
        }
    },
    mutations: {
          setSession (state, payload) {
              state.session = payload
          }
    },
    actions: {
          getSession({commit}) {
              return axios.post(this.state.sessionEndpoint)
                  .then((response) => {
                      commit('setSession', response.data.data)
                      return response.data.data
                  })
                  .catch((error) => {
                      console.log(error)
                      window.location.href = '/error'
                  });
          }
    },
    modules: {},
    getters: {
        session: (state) => {
            return state.session
        },
        isAuthed: (state) => {
            return state.session.user && window.localStorage.getItem('user_data') && window.localStorage.getItem('token')
        }
    }
})
