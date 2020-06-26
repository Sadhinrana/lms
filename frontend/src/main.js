import Vue from "vue";
import App from "./App.vue";
import state from './modules/state/state'
import router from './modules/router/router'
import axios from 'axios'
import routes from './modules/router/routes'
import lodash from 'lodash'
import VueSession from 'vue-session'
import VueMathjax from 'vue-mathjax'

import VueKatex from 'vue-katex'
import 'katex/dist/katex.min.css';



export const eventBus = new Vue()

window._ = lodash
window.axios = axios
window.eventBus = eventBus

Vue.use(VueSession, {persist: true})
Vue.config.productionTip = false;
Vue.prototype.enableLogging = true;
Vue.prototype.routes = routes
Vue.use(VueMathjax)
Vue.use(VueKatex)

if (state.getters.isAuthorized)
    axios.defaults.headers.common["Authorization"] =
        "Bearer " + state.getters.token;

//Create Bus for event $emit


new Vue({
    render: h => h(App),
    store: state,
    router
}).$mount("#app");
