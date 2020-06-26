import Vue from 'vue'
import Vuex from 'vuex'
import { stat } from 'fs';
Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    auth_token: localStorage.getItem("auth_token"),
    is_authorized: !!localStorage.getItem("auth_token"),
    user: {},
    is_loading:false
  },

  getters:{
    isAuthorized(state){
      return state.is_authorized
    },

    authUser(state){
      return state.user
    },

    token(state){
      return state.auth_token
    },

    loading(state){
      return state.is_loading
    }
  },

  mutations: {
    setAuthToken(state, token) {
      state.auth_token = token
      state.is_authorized = true
      localStorage.setItem("auth_token", token)
    },

    destroyToken(state) {
      state.auth_token = ""
      state.is_authorized = false
      localStorage.removeItem("auth_token")
    },

    setAuthUserData(state, userData) {
      state.user = userData
    },

    destroyAuthUserData(state) {
      state.user = {}
      state.is_authorized = false
      state.auth_token = ""
      localStorage.removeItem("auth_token")
    },

    enableLoadingState(state) {
      state.is_loading = true
    },

    disableLoadingState(state) {
      state.is_loading = false
    },
  },

  actions: {
    setAuthToken(context, token) {
      context.commit("setAuthToken", token)
    },

    destroyToken(context) {
      context.commit("destroyToken")
    },

    setAuthUserData(context, user_data) {
      context.commit("setAuthUserData", user_data)
    },

    destroyAuthUserData(context) {
      context.commit("destroyAuthUserData")
    },

    enableLoading(context) {
      context.commit("enableLoadingState")
    },

    disableLoading(context) {
      context.commit("disableLoadingState")
    },
  }
})

export default store
