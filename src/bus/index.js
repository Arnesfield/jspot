import Vue from 'vue'
import nav from './nav'
import fab from './fab'
import dialog from './dialog'
import session from './session'
import progress from './progress'
import settings from './settings'

export default new Vue({
  data: () => ({
    nav: nav,
    fab: fab,
    dialog: dialog,
    session: session,
    progress: progress,
    settings: settings
  }),

  watch: {
    'session.auth': function(to, from) {
      this.$emit('get-route', 'change--session.auth')
    },
    'dialog.ManageUsers.add': function(to, from) {
      this.$emit('dialog--manage-user.add', to, from)
    },
    'dialog.global.delete': function(to, from) {
      this.$emit('dialog--global.delete', to, from)
    }
  },

  methods: {
    navToggle() {
      if (this.nav.model) {
        if (this.nav.miniVariant) {
          this.nav.model = false
        } else {
          this.nav.miniVariant = true
        }
      } else {
        this.nav.model = true
        this.nav.miniVariant = false
      }
    },

    authCheck(routeAuth) {
      // convert routeAuth to array
      if (typeof routeAuth !== 'object') {
        routeAuth = [routeAuth]
      }
      return routeAuth.indexOf(Number(this.session.auth)) > -1
        && routeAuth.indexOf(10) == -1
        && routeAuth.indexOf(0) == -1
    },

    authHas(auth, n) {
      // convertto array
      if (typeof auth !== 'object') {
        auth = [auth]
      }
      return auth.indexOf(typeof n === 'number' ? n : Number(n)) > -1
    },

    sessionCheck(route, http) {
      http.post('/sess').then((res) => {
        this.sessionSet(res.data)
        // change--session.auth is called when session.auth is changed
      }).catch(e => {
        console.error(e)
      })
    },
    sessionSet(data) {
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
     
      const settingsFields = [
        { key: 'dark', type: 'boolean', def: false }
      ]

      let settings = JSON.parse(data.user.settings)
      settingsFields.forEach(e => {
        this.settings[e] = settings && typeof settings[e] === e.type ? settings[e] : e.def
      })
    }
  }
})
