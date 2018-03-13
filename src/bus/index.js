import Vue from 'vue'
import nav from './nav'
import fab from './fab'
import tabs from './tabs'
import search from './search'
import dialog from './dialog'
import profile from './profile'
import session from './session'
import progress from './progress'
import settings from './settings'
import toNumberArray from '@/assets/js/toNumberArray'

export default new Vue({
  data: () => ({
    nav: nav,
    fab: fab,
    tabs: tabs,
    search: search,
    dialog: dialog,
    profile: profile,
    session: session,
    progress: progress,
    settings: settings
  }),

  watch: {
    'session.auth': function(to, from) {
      if (to == 0) {
        this.nav.model = null
      }
      this.$emit('get-route', 'change--session.auth')
    },
    'dialog.ManageUsers.add': function(to, from) {
      this.$emit('dialog--manage-user.add', to, from)
    },
    'dialog.global.delete': function(to, from) {
      this.$emit('dialog--global.delete', to, from)
    },
    'profile.listView': function(to, from) {
      this.$emit('watch--profile.listView', to, from)
    }
  },

  methods: {
    setFabViewer() {
      this.fab.inst.Profile.hidden = true
      this.fab.inst.Profile.click = null
    },
    setFabViewerLogged() {
      this.fab.inst.Profile.hidden = false
      this.fab.inst.Profile.before = 'rate_review'
      this.fab.inst.Profile.tip = 'Submit a review'
      this.fab.inst.Profile.click = 'profile--review'
    },
    setFabMyself() {
      this.fab.inst.Profile.hidden = false
      this.fab.inst.Profile.before = 'edit'
      this.fab.inst.Profile.tip = 'Edit'
      this.fab.inst.Profile.click = 'profile--edit'
    },

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
      // check if routeAuth in session auth
      return this.authHas(routeAuth, this.session.auth)
        && !this.authHas(routeAuth, [0, 10])
    },

    authHas(auth, n, concat) {
      // convert to array
      auth = toNumberArray(auth)
      // also convert n to array
      n = toNumberArray(n)
      
      // do concat
      if (typeof concat === 'object' || typeof concat === 'number') {
        n = toNumberArray(n.concat(concat))
      }
      // check if some n exists in auth
      let result = false
      auth.every(e => !(result = n.indexOf(e) > -1))
      return result
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
      if (!data.user || !data.user.settings) {
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
