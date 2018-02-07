<template>
<v-container fluid>
  <v-layout>
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
                prepend-icon="account_circle"
                :disabled="disableEmail"
                label="Email"
                type="email"
              />
              <v-text-field
                prepend-icon="lock"
                label="Password"
                :disabled="disablePassword"
                :append-icon="hidePass ? 'visibility' : 'visibility_off'"
                :append-icon-cb="() => (hidePass = !hidePass)"
                :type="hidePass ? 'password' : 'text'"
              />
            </v-form>
          </v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              :disabled="disableLogin"
              @click="submit">Login</v-btn>
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
export default {
  name: 'login',
  data: () => ({
    disableEmail: false,
    disablePassword: false,
    disableLogin: false,
    loading: false,
    hidePass: true
  }),
  methods: {
    submit() {
      this.lockFields()
      // request here
    },
    lockFields() {
      this.loading = true
      this.disableEmail = true
      this.disablePassword = true
      this.disableLogin = true
    },
    unlockFields() {
      this.loading = false
      this.disableEmail = false
      this.disablePassword = false
      this.disableLogin = false
    }
  }
}
</script>
