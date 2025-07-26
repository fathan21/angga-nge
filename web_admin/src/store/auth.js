import Cookies from "../plugins/cookies";

import axios from "../plugins/axios";

const user = JSON.parse(Cookies.get("user"));

export default {
  namespaced: true,
  state: {
    accessToken: Cookies.get("access_token") || null,
    tokenExpiresIn: Cookies.get("expires_in") || null,
    tokenType: Cookies.get("token_type") || null,
    refreshToken: Cookies.get("refresh_token") || null,
    user: user ? user : {},
    loadingUserInfo: false,
    loggedIn: Cookies.get("access_token") ? true : false,
    notif: {
      topup_request: 0,
      air_request_issued: 0,
      air_request_refund_rebook: 0,
    },
    menuApidoc: true,
    isMarketing: false,
  },
  actions: {
    login({ commit }, user) {
      commit("loginSuccess", user);
      // Cookies.set("user", JSON.stringify(user.user));
      Cookies.set("access_token", user.access_token);
      Cookies.set("expires_in", user.expires_in);
      Cookies.set("refresh_token", user.refresh_token);
    },
    logout({ commit }) {
      Cookies.clear("access_token");
      Cookies.clear("expires_in");
      Cookies.clear("refresh_token");

      axios.get("/logout");
      commit("logout");
    },
    getUserInfo({commit}) {
      commit('setLoadingUserInfo', true);
      axios.get(`/app/user-info`).then(v=> {      
        commit("setUser", v.data);
        if(v.data && v.data.role  && v.data.role.length == 1 ) {
          if(v.data.role[0] == 'marketing') {
            commit('setMenuApidoc', false);
            commit('isMarketing', true);
          }
        }
      }).finally(()=>{
        commit('setLoadingUserInfo', false);
      });
    },
    getNotif({commit, state}) {
      if (state.user) {
          axios.get(`/app/notif`).then(v=> {      
            commit("setNotif", v.data);
          });
      }
    },
  },
  mutations: {
    loginSuccess(state, user) {
      state.loggedIn = true;
      // state.user = user.user;
      state.access_token = user.access_token;
      state.expires_in = user.expires_in;
      state.refresh_token = user.refresh_token;
    },
    setLoadingUserInfo(state, payload) {
      state.loadingUserInfo = payload;
    },
    loginFailure(state) {
      state.loggedIn = false;
      state.user = null;
    },
    setMenuApidoc(state, payload) {
      state.menuApidoc = payload;
    },
    setUser(state, payload) {
      state.user = payload;
    },
    setNotif(state, payload) {
      state.notif = payload;
    },
    isMarketing(state, payload) {
      state.isMarketing = payload;
    },
    logout(state) {
      state.loggedIn = false;
      state.accessToken = null;
      state.tokenType = null;
      state.refreshToken = null;
      state.user = null;
    },
    registerSuccess(state) {
      state.loggedIn = false;
    },
    registerFailure(state) {
      state.loggedIn = false;
    },
  },
  getters: {
    loggedIn(state) {
      return state.loggedIn;
    },
    loadingUserInfo(state) {
      return state.loadingUserInfo;
    },
    isMarketing(state) {
      return state.isMarketing;
    },
    menuApidoc(state) {
      return state.menuApidoc;
    },
    accessToken(state) {
      return state.accessToken;
    },
    user(state) {
      return state.user;
    },
    notif(state) {
      return state.notif;
    },
  },
};
