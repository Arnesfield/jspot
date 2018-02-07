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
      this.$emit('get-route', 'change--session.auth')
    }
  },

  computed: {
    componentWithAuth() {
      return this.session.auth == 2
    }
  },

  methods: {
    checkSession(route, http) {
      http.post('/sess').then((res) => {
        console.error(res)
        console.error(res.headers)
        this.setSession(res.data)
        this.$emit('change--session.auth', route)
      }).catch(e => {
        console.error(e)
      })
    },
    setSession(data) {
      const fields = ['user', 'auth']
      fields.forEach(e => {
        if (data[e]) {
          this.session[e] = data[e]
        }
      })
    }
  }
})
