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
    :style="noData ? {
      'height': 'calc(100% - 128px)'
    } : null"
  >
    <template v-if="$bus.profile.type == 3">
      <v-tab-item :style="reviews ? { height: '100%' } : null">
        <reviews v-model="reviews" :user="user"/>
      </v-tab-item>
      <v-tab-item :style="jobOpenings ? { height: '100%' } : null">
        <job-openings
          v-model="jobOpenings"
          :user="user"
        />
      </v-tab-item>
    </template>

    <template v-else-if="$bus.profile.type == 4">
      <v-tab-item :style="reviews ? { height: '100%' } : null">
        <reviews v-model="reviews" :user="user"/>
      </v-tab-item>
    </template>

  </v-tabs-items>

  <dialog-review/>
  <dialog-add-user
    v-if="user"
    userMode
    :mode="dialogMode"
    :userToEdit="userToEdit"
  />

</span>
</template>

<script>
import ProfileNav from '@/include/nav/ProfileNav'
import ManageNoData from '@/include/ManageNoData'
import DialogReview from '@/include/dialogs/DialogReview'
import DialogAddUser from '@/include/dialogs/DialogAddUser'
import JobOpenings from '@/include/JobOpenings'
import Reviews from '@/include/Reviews'
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
    DialogAddUser,
    JobOpenings,
    Reviews
  },
  data: () => ({
    url: '/users/profile',
    logsViewedUrl: '/logs/viewed',
    user: null,
    loading: false,
    jobOpenings: true,
    reviews: true,
    dialogMode: 'Edit',
    userToEdit: null
  }),
  computed: {
    noData() {
      if (this.$bus.profile.type == 3) {
        if (this.$bus.tabs.profile == '0') {
          return this.jobOpenings
        } else if (this.$bus.tabs.profile == '1') {
          return this.reviews
        }
      }
      else if (this.$bus.profile.type == 4) {
        if (this.$bus.tabs.profile == '0') {
          return this.reviews
        }
      }
      return false
    },
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
    this.$bus.$on('update--profile-nav', this.checkAuth)
    this.$bus.$on('profile--edit', this.profileEdit)
    this.$bus.$on('profile--review', this.profileReview)
    this.$bus.$on('update--manage-users', this.checkAuth)
    this.$bus.$on('dialog--manage-user.add', this.manageUserAdd)
    this.insertViewed()
    this.checkAuth()
  },
  beforeDestroy() {
    this.$bus.$off('change--session.auth', this.checkAuth)
    this.$bus.$off('update--profile-nav', this.checkAuth)
    this.$bus.$off('profile--edit', this.profileEdit)
    this.$bus.$off('profile--review', this.profileReview)
    this.$bus.$off('update--manage-users', this.checkAuth)
    this.$bus.$off('dialog--manage-user.add', this.manageUserAdd)
  },

  methods: {
    manageUserAdd(to, from) {
      if (to) {
        this.userToEdit = JSON.parse(JSON.stringify(this.user))
      } else {
        this.userToEdit = null
      }
    },
    profileEdit() {
      this.$bus.dialog.ManageUsers.add = true
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
