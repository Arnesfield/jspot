<template>
<v-container class="full-height" fluid>
  <v-layout class="center-y full-height">
    <v-flex hidden-xs-only sm2 md3 lg4/>
    <v-flex sm8 md6 lg4>
      <v-card>
        <v-progress-linear
          height="3"
          indeterminate
          :active="loading"/>
        <div class="px-4 pb-4 pt-2">
          <v-card-title primary-title>
            <div>
              <div class="headline">Sign In</div>
              <span class="grey--text">With JSpot, hire or get hired!</span>
            </div>
          </v-card-title>
          <v-card-title>
            <v-form style="width: 100%">
              <v-text-field
                ref="email"
                type="email"
                label="Email"
                v-model="email"
                prepend-icon="account_circle"
                :disabled="loading"
                :error-messages="emailError"
              />
              <v-text-field
                ref="password"
                label="Password"
                v-model="password"
                prepend-icon="lock"
                :disabled="loading"
                :append-icon="hidePass ? 'visibility' : 'visibility_off'"
                :append-icon-cb="() => (hidePass = !hidePass)"
                :type="hidePass ? 'password' : 'text'"
                :error-messages="passwordError"
              />
            </v-form>
          </v-card-title>
          <v-card-actions>
            <div class="caption grey--text">
              <span v-if="loading">Forgot password</span>
              <router-link
                to="/"
                v-else
                class="clean-a text--accent"
              >Forgot password</router-link>?
            </div>
            <v-spacer/>
            <v-btn
              color="primary"
              :disabled="loading"
              @click="submit"
            >Login</v-btn>
            <v-btn
              to="/"
              :disabled="loading"
            >Sign Up</v-btn>
          </v-card-actions>
        </div>

      </v-card>
      
      <v-layout class="pt-3">
        <v-flex xs-6>
          <div class="caption grey--text">&copy; jspot {{ (new Date()).getFullYear() }}</div>
        </v-flex>
        <v-flex xs-6>
          <div class="caption grey--text text-xs-right">group name</div>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</v-container>
</template>

<script>
import qs from 'qs'

export default {
  name: 'login',
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
        console.error(res.data)
        if (!res.data.success) {
          this.loading = false
          this.emailError = ''
          this.passwordError = res.data.error
          return
        }

        this.$bus.checkSession(this.$route, this.$http)
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
