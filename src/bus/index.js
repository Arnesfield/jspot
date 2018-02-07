import Vue from 'vue'
import nav from './nav'
import session from './session'

export default new Vue({
  data: () => ({
    nav: nav,
    session: session
  }),

  watch: {
    'session.auth': function(to, from) {
      this.$emit('get-route', 'change--session.auth', to, from)
    }
  },

  computed: {
    componentWithAuth() {
      return this.session.auth == 2
    }
  }
})
