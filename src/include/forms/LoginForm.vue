<template>
<v-card>
  <v-progress-linear
    height="4"
    indeterminate
    color="warning"
    :active="loading"
    :class="{ 'mt-0': dialog }"
  />
  <div class="px-4 pb-4 pt-2">
    <v-card-title primary-title>
      <div>
        <div class="headline">Sign In</div>
        <span class="grey--text">With JSpot, hire or get hired!</span>
      </div>
    </v-card-title>
    <v-card-title>
      <v-form class="full-width">
        <v-text-field
          ref="email"
          type="email"
          label="Email"
          tabindex="1"
          v-model="email"
          prepend-icon="account_circle"
          :disabled="loading"
          :error-messages="emailError"
          @keypress.enter="submit"
        />
        <v-text-field
          ref="password"
          label="Password"
          tabindex="1"
          v-model="password"
          prepend-icon="lock"
          :disabled="loading"
          :append-icon="hidePass ? 'visibility' : 'visibility_off'"
          :append-icon-cb="() => (hidePass = !hidePass)"
          :type="hidePass ? 'password' : 'text'"
          :error-messages="passwordError"
          @keypress.enter="submit"
        />
      </v-form>
    </v-card-title>
    <v-card-actions>
      <div class="caption grey--text">
        <span v-if="loading">Forgot password</span>
        <router-link
          to="/"
          v-else
          class="clean-a text--accent-1"
        >Forgot password</router-link>?
      </div>
      <v-spacer/>
      <v-btn
        to="/signup"
        flat
        color="primary"
        @click="dialogCloseFn"
        :disabled="loading"
      >Sign Up</v-btn>
      <v-btn
        tabindex="1"
        color="primary"
        :disabled="loading"
        @click="submit"
        @keypress.enter="submit"
        type="submit"
      >Login</v-btn>
    </v-card-actions>
  </div>

</v-card>
</template>

<script>
import qs from 'qs'

export default {
  name: 'login-form',
  props: {
    dialog: {
      type: Boolean,
      default: false
    },
    dialogCloseFn: {
      type: Function,
      default: () => {}
    }
  },
  data: () => ({
    url: '/login',
    email: null,
    password: null,
    emailError: [],
    passwordError: [],
    // view
    loading: false,
    hidePass: true
  }),
  watch: {
    email(to, from) {
      if (to !== null && to !== from) {
        this.emailError = []
      }
    },
    password(to, from) {
      if (to !== null && to !== from) {
        this.passwordError = []
      }
    }
  },

  mounted() {
    this.$refs.email.focus()
  },

  methods: {
    submit() {
      this.loading = true
      this.emailError = []
      this.passwordError = []
      // request here
      this.$http.post(this.url, qs.stringify({
        email: this.email,
        password: this.password,
      })).then((res) => {
        if (!res.data.success) {
          this.loading = false
          this.emailError = ''
          this.passwordError = res.data.error
          return
        }

        // also call dialog fn if dialog
        if (this.dialog && typeof this.dialogCloseFn === 'function') {
          this.dialogCloseFn()
        }
        this.$bus.$emit('snackbar--show', 'Login successfully.')
        this.$bus.sessionCheck(this.$route, this.$http)
      }).catch(e => {
        console.error(e)
        this.loading = false
        this.emailError = ''
        this.passwordError = 'An error occurred.'
      })
    }
  }
}
</script>
