import { createStore } from "vuex";
import auth from './auth';

export default createStore({
  state: {
    loading: false,
    user: {}
  },
  mutations: {
    loading(state, loading) {
      state.loading = loading;
    }
  },
  actions: {
    loading({ commit }, loading) {
      commit("loading", loading);
    }
  },
  getters: {
    loading(state) {
      return state.loading;
    }
  },
  modules: {
    auth
  }
  
});
