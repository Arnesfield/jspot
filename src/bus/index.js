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
        this.setSession(res.data)
        this.$emit('change--session.auth', route)
      }).catch(e => {
        console.error(e)
      })
    },
    setSession(data) {
      const fields = [
        { key: 'user', def: null },
        { key: 'auth', def: 1 }
      ]
      fields.forEach(e => {
        if (data[e.key]) {
          this.session[e.key] = data[e.key]
        } else {
          // set default value
          this.session[e.key] = e.def
        }
      })
    }
  }
})
