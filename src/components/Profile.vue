<template>
<span>
  <profile-nav
    :user="user"
    :editable="editable"
  />
  <v-container v-if="!user" style="height: calc(100% - 64px); display: flex">
    <v-layout justify-center align-center>
      <manage-no-data
        :loading="loading"
        :fetch="() => { fetch(id) }"
        msg="Unable to load user profile :(<br/>User might not exist."
      >
        <div slot="icon" class="mb-3">
          <v-icon size="64px">account_circle</v-icon>
        </div>
      </manage-no-data>
    </v-layout>
  </v-container>

  <v-tabs-items
    v-model="$bus.tabs.profile"
    :style="jobOpenings ? {
      'height': 'calc(100% - 128px)'
    } : null"
  >
    <v-tab-item
      :style="jobOpenings ? {
        'height': '100%'
      } : null"
    >
      <!-- tab items for employer -->
      <template v-if="$bus.profile.type == 3">
        <job-openings v-model="jobOpenings" :user="user"/>
      </template>
      <!-- tab items for employee -->
      <template v-if="$bus.profile.type == 4">
        Test
      </template>
    </v-tab-item>
  </v-tabs-items>

  <dialog-review/>

</span>
</template>

<script>
import ProfileNav from '@/include/nav/ProfileNav'
import ManageNoData from '@/include/ManageNoData'
import DialogReview from '@/include/dialogs/DialogReview'
import JobOpenings from '@/include/JobOpenings'
import qs from 'qs'

export default {
  name: 'profile',
  props: {
    id: [Number, String]
  },
  components: {
    ProfileNav,
    ManageNoData,
    DialogReview,
    JobOpenings
  },
  data: () => ({
    url: '/users/profile',
    logsViewedUrl: '/logs/viewed',
    user: null,
    loading: false,
    jobOpenings: true
  }),
  computed: {
    editable() {
      // editable if id is same as session uid
      let id = null
      if (this.$bus.session.auth != 0) {
        if (typeof this.id !== 'undefined' || this.id === null) {
          id = this.id
        } else {
          id = this.$bus.session.user.id
        }
      } else {
        return false
      }
      return id == this.$bus.session.user.id
    }
  },
  watch: {
    user(e) {
      this.$bus.profile.type = e ? e.type : null
    },
    id(to, from) {
      if (to != from) {
        this.checkAuth()
      }
    },
    loading(e) {
      this.$bus.progress.active = e
    }
  },
  created() {
    this.$bus.$on('change--session.auth', this.checkAuth)
    this.$bus.$on('profile--edit', this.profileEdit)
    this.$bus.$on('profile--review', this.profileReview)
    this.insertViewed()
    this.checkAuth()
  },
  beforeDestroy() {
    this.$bus.$off('change--session.auth', this.checkAuth)
    this.$bus.$off('profile--edit', this.profileEdit)
    this.$bus.$off('profile--review', this.profileReview)
  },

  methods: {
    profileEdit() {

    },
    profileReview() {
      if (typeof this.id === 'undefined' || this.id === null) {
        return
      }
      this.$bus.$emit('dialog--review', this.id)
    },

    insertViewed() {
      if (typeof this.id === 'undefined' || this.id === null) {
        return
      }
      this.$http.post(this.logsViewedUrl, qs.stringify({
        id: this.id
      })).then(res => {}).catch(e => {
        console.error(e)
      })
    },
    checkAuth() {
      // if id not set, get sess id
      // if sess id not set, redirect to login
      // always permit with id
      if (typeof this.id !== 'undefined' && this.id !== null) {
        // if id exists
        this.fetch(this.id)
        // if id is the same with sess
        if (this.$bus.session.user) {
          if (this.$bus.session.user.id == this.id) {
            // if the same, that's myself
            this.$bus.setFabMyself()
          } else {
            // if not the same
            this.$bus.setFabViewerLogged()
          }
        } else {
          // if not logged in
          this.$bus.setFabViewer()
        }
      } else {
        // if id is empty and user exists
        if (this.$bus.session.auth == 0) {
          this.$bus.setFabViewer()
          this.$router.push('/login')
        } else {
          let id = this.$bus.session.user.id
          this.fetch(id)
          this.$bus.setFabMyself()
        }
      }
    },
    fetch(id) {
      if (typeof id === 'undefined') {
        id = this.$bus.session.user ? this.$bus.session.user.id : false
      }
      this.loading = true
      this.$http.post(this.url, qs.stringify({
        id: id
      })).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.user = res.data.user
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
        // also remove user
        this.user = null
        this.$bus.$emit('snackbar--show', {
          text: 'Unable to load user profile.',
          btns: {
            text: 'Retry',
            icon: false,
            color: 'accent',
            cb: (sb, e) => {
              // fn(onSuccess, onError, close, fn)
              this.fetch(id)
              sb.snackbar = false
            }
          }
        })
      })
    }
  }
}
</script>
