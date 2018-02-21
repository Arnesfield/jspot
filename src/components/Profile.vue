<template>
<span>
  <profile-nav
    :user="user"
    :editable="editable"
  />
  <v-container
    :style="!user ? {
      'height': 'calc(100% - 64px)',
      'display': 'flex'
    } : null"
  >

    <template v-if="!user">
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
    </template>

  </v-container>
</span>
</template>

<script>
import ProfileNav from '@/include/nav/ProfileNav'
import ManageNoData from '@/include/ManageNoData'
import qs from 'qs'

export default {
  name: 'profile',
  props: {
    id: [Number, String]
  },
  components: {
    ProfileNav,
    ManageNoData
  },
  data: () => ({
    url: '/users/profile',
    user: null,
    loading: false,
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
    this.checkAuth()
  },

  methods: {
    checkAuth() {
      // if id not set, get sess id
      // if sess id not set, redirect to login
      // always permit with id
      if (typeof this.id !== 'undefined' && this.id !== null) {
        // if id exists
        this.fetch(this.id)
      } else {
        // if id is empty and user exists
        if (this.$bus.session.auth == 0) {
          this.$router.push('/login')
        } else {
          let id = this.$bus.session.user.id
          this.fetch(id)
        }
      }
    },
    fetch(id) {
      if (typeof id === 'undefined') {
        id = this.$bus.session.user.id
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
