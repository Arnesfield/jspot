// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
// import Vuetify from 'vuetify'

import bus from './bus'
import routerCond from './router/cond'
import formRules from './assets/js/formRules'
import wrap from './assets/js/wrap'
import _baseURL from './assets/js/baseURL'
import colors from 'vuetify/es5/util/colors'

// import 'vuetify/dist/vuetify.min.css'
import './assets/css/common.css'
import './assets/css/override.css'

const baseURL = _baseURL + 'api'
const http = axios.create({
  baseURL: baseURL,
  withCredentials: true
})

Vue.use(Vuetify, {
  theme: {
    primary: colors.blue.darken1,
    accent: colors.yellow.accent3,
    warning: colors.deepOrange.lighten1
  }
})

Vue.config.productionTip = false
Vue.prototype.$http = http
Vue.prototype.$bus = bus
Vue.prototype.$vfRule = formRules
Vue.prototype.$wrap = wrap

routerCond(router, http, bus)

/* eslint-disable no-new */
// before creating instance, check if session exists
http.post('/sess').then((res) => {
  document.getElementById('loading').style.display = 'none'
  if (!res.data.success) {
    throw new Error('Request failure.')
  }
  bus.sessionSet(res.data)
  new Vue({
    el: '#app',
    router,
    components: { App },
    template: '<App/>'
  })
}).catch(e => {
  console.error(e)
})
