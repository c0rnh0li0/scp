import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
      sessionEndpoint: '/api/auth',
      session: {}
  },
  mutations: {
      setSession (state, sessionData) {
          state.session = sessionData
      }
  },
  actions: {
      async getSessionData ({ commit }) {
          commit('setSession', await getServerSession())
      },
      async getServerSession() {

      }
  },
  modules: {
  }
})
