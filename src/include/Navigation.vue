<template>
<v-navigation-drawer
  :app="$route.name != 'Profile'"
  fixed
  :mini-variant="$bus.nav.miniVariant"
  :clipped="$bus.nav.clipped"
  v-model="$bus.nav.model"
  :temporary="$route.name == 'Profile'"
  :style="
    $route.name == 'Profile'
    ? { 'max-height': '100%' } : null
  "
>
  <nav-user/>
  <v-list
    class="pb-0"
    :class="{ 'py-0': i != 0 }"
    :key="i"
    v-if="$bus.authHas(list.auth, $bus.session.auth, 10)"
    v-for="(list, i) in lists"
    :subheader="Boolean(list.header)"
  >
    <v-subheader
      class="grey--text"
      v-if="list.header"
    >{{ list.header }}</v-subheader>
    <template v-for="(item, i) in list.items">
      <v-divider :key="i" v-if="typeof item !== 'object'"/>
      <v-tooltip
        :key="i"
        v-else
        :disabled="!$bus.nav.miniVariant"
        right
      >
        <v-list-tile
          ripple
          slot="activator"
          :to="item.to"
          @keypress.enter="item.globalClick ? $bus.$emit(item.click) : listItemClick(item.click)"
          @click="item.globalClick ? $bus.$emit(item.click) : listItemClick(item.click)"
          :exact-active-class="item.to"
          tabindex="1"
        >
          <v-list-tile-action>
            <v-icon v-html="item.icon"></v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title v-text="item.title"></v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <span>{{ item.title }}</span>
      </v-tooltip>
    </template>
  </v-list>

  <v-list class="pa-0">
    <v-tooltip right :disabled="!$bus.nav.miniVariant">
      <v-list-tile
        ripple
        tabindex="1"
        slot="activator"
        @click="$bus.nav.miniVariant = !$bus.nav.miniVariant">
        <v-list-tile-action>
          <v-btn
            icon
            tabindex="1"
            :ripple="false"
            @click.stop="$bus.nav.miniVariant = !$bus.nav.miniVariant">
            <v-icon v-html="$bus.nav.miniVariant ? 'chevron_right' : 'chevron_left'"/>
          </v-btn>
        </v-list-tile-action>
        <v-list-tile-content>{{ collapseText }}</v-list-tile-content>
      </v-list-tile>
      <span>{{ collapseText }}</span>
    </v-tooltip>
  </v-list>
</v-navigation-drawer>
</template>

<script>
import NavUser from './nav/NavUser'

export default {
  name: 'navigation',
  components: {
    NavUser
  },
  data: () => ({
    logoutUrl: '/logout',
    lists: [
      {
        header: '',
        auth: [0, 3, 4],
        items: [
          { title: 'Dashboard', icon: 'dashboard', to: '/dashboard' }
        ]
      },
      {
        header: '',
        auth: [3, 4],
        items: [
          { title: 'Profile', icon: 'account_circle', to: '/profile' }
        ]
      },
      {
        header: '',
        auth: 3,
        items: [
          { title: 'My job openings', icon: 'work', to: '/my/jobs' }
        ]
      },
      {
        header: '',
        auth: 3,
        items: [
          { title: 'Applicants', icon: 'people', to: '/my/applicants' }
        ]
      },
      {
        header: '',
        auth: 4,
        items: [
          { title: 'My job applications', icon: 'work', to: '/my/applications' }
        ]
      },
      {
        header: '',
        auth: [3, 4],
        items: [
          { title: 'Boosts', icon: 'trending_up', to: '/boosts' }
        ]
      },
      {
        header: 'Manage',
        auth: 2,
        items: [
          { title: 'Users', icon: 'people', to: '/manage/users' },
        ]
      },
      // login
      {
        auth: 0,
        items: [
          '',
          { title: 'Login', icon: 'exit_to_app', click: 'dialog--login', globalClick: true }
        ]
      },
      // logout
      {
        auth: [2, 3, 4],
        items: [
          '',
          { title: 'Logout', icon: 'exit_to_app', click: 'logout' }
        ]
      }
    ],
    loading: false
  }),

  computed: {
    collapseText() {
      return this.$bus.nav.miniVariant ? 'Expand' : 'Collapse'
    }
  },

  watch: {
    '$route': function(to, from) {
      if (to.name == 'Profile') {
        // also reset tabs
        this.$bus.tabs.profile = '0'
      }
      if (to.name != 'Profile' && from.name == 'Profile') {
        this.$bus.nav.model = false
        setTimeout(() => {
          this.$bus.navToggle()
        }, 100)
      }
    },
    loading(e) {
      this.$bus.progress.active = e
    }
  },

  methods: {
    logout() {
      // logout here
      this.loading = true
      this.$http.post(this.logoutUrl).then((res) => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.$bus.$emit('snackbar--show', 'Logout successfully.')
        // unregister listeners
        // this.$bus.$off([
        //   'dialog--job-apply.update-applicants',
        //   'add--job-opening',
        //   'update--my-job-openings',
        //   'add--user',
        //   'dialog--manage-user.add',
        //   'update--manage-users',
        //   'update--dashboard',
        //   'dialog--manage-user.add',
        //   'dialog--apply-action.show',
        //   'dialog--global.delete',
        //   'dialog--delete.show',
        //   'dialog--job.apply',
        //   'dialog--job-apply.update-applicants',
        //   'dialog--job-opening.add',
        //   'watch--profile.listView',
        //   'add--job-opening',
        //   'update--my-job-openings',
        //   'update--dashboard'
        // ])
        this.$bus.sessionCheck(this.$route, this.$http)
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },

    listItemClick(e) {
      if (typeof e === 'string') {
        this[e]()
      }
    }
  }
}
</script>
