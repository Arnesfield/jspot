// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import Vuetify from 'vuetify'
import bus from './bus'

import 'vuetify/dist/vuetify.min.css'

const dev = true
const baseURL = dev ? 'http://localhost/jspot/public/api' : 'to be set'
const http = axios.create({ baseURL: baseURL })

Vue.use(Vuetify)

Vue.config.productionTip = false
Vue.prototype.$http = http
Vue.prototype.$bus = bus

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
