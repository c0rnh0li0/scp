import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        sessionEndpoint: '/api/auth',
        session: {}
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
                  })
                  .catch((error) => {
                      console.log(error)
                  });
          }
    },
    modules: {},
    getters: {
        session: (state) => {
            return state.session
        }
    }
})
