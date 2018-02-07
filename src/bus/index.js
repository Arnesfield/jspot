import Vue from 'vue'
import nav from './nav'
import fab from './fab'
import session from './session'
import progress from './progress'
import settings from './settings'

export default new Vue({
  data: () => ({
    nav: nav,
    fab: fab,
    session: session,
    progress: progress,
    settings: settings
  }),

  watch: {
    'session.auth': function(to, from) {
      this.$emit('get-route', 'change--session.auth')
    }
  },

  computed: {
    componentWithAuth() {
      return this.session.auth >= 2
    }
  },

  methods: {
    checkSession(route, http) {
      http.post('/sess').then((res) => {
        this.setSession(res.data)
        // change--session.auth is called when session.auth is changed
      }).catch(e => {
        console.error(e)
      })
    },
    setSession(data) {
      const fields = [
        { key: 'user', def: null },
        { key: 'auth', def: 0 }
      ]
      fields.forEach(e => {
        // set default value if data does not exist
        this.session[e.key] = data[e.key] || e.def
      })
      // set settings if user exists
      if (!data.user) {
        return
      }
      let settings = JSON.parse(data.user.settings)
      Object.keys(this.settings).forEach(e => {
        if (settings[e]) {
          this.settings[e] = settings[e]
        }
      })
    }
  }
})
