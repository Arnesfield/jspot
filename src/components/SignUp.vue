<template>
<v-layout
  row wrap
  fill-height
>
  <v-flex
    xs12 sm12 md7
    v-bind="{
      lg8: stepper == '1',
      lg6: stepper == '2'
    }"
    style="transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1)"
  >
  <v-jumbotron
    src="/static/images/bg-1.jpg"
    dark
    height="100%"
    class="elevation-4"
  >
    <div class="bg-dim full-height">
      <v-container fill-height>
        <v-layout style="display: flex; justify-content: center; align-items: center;">
          <div v-if="stepper == '1'">
            <h3 class="display-3">Sign Up</h3>
            <span class="medium">
              <template>Already have an account?</template>
              <router-link
                to="/login"
                class="white--text"
              >Login</router-link>
              <template>or</template>
            </span>
            <v-btn
              to="/dashboard"
              large
              color="warning"
            >Go to Dashboard</v-btn>
          </div>
          <div v-else-if="stepper == '2'">
            <h3 class="display-3">Account created!</h3>
            <span class="medium">Verify your account then</span>
            <v-btn
              to="/login"
              large
              color="warning"
            >Login</v-btn>
          </div>
        </v-layout>
      </v-container>
    </div>
  </v-jumbotron>
  </v-flex>

  <v-flex
    xs12 sm12 md5 
    v-bind="{
      lg4: stepper == '1',
      lg6: stepper == '2'
    }"
  >
  <v-container class="px-2">
    <v-stepper v-model="stepper" vertical>
      <v-progress-linear
        height="4"
        indeterminate
        color="warning"
        :active="loading"
        class="my-0"
      />
      <!-- <v-stepper-header> -->
        <!-- <v-divider></v-divider> -->
        
        <!-- <v-divider></v-divider> -->
        
      <!-- </v-stepper-header> -->
      <v-stepper-step step="1" :complete="stepper > 1">Create an account</v-stepper-step>
      <v-stepper-items>
        <v-stepper-content step="1">

          <v-form ref="form" v-model="form">

            <v-subheader>Basic Information</v-subheader>
            <v-layout row>
              <v-flex xs12 sm6>
                <v-text-field
                  label="First name"
                  v-model="fname"
                  :disabled="loading"
                  prepend-icon="account_circle"
                  :rules="[$vfRule('required')]"
                  required
                />
              </v-flex>
              &nbsp;
              <v-flex xs12 sm6>
                <v-text-field
                  label="Last name"
                  v-model="lname"
                  :disabled="loading"
                  :rules="[$vfRule('required')]"
                  required
                />
              </v-flex>
            </v-layout>
            <v-text-field
              type="email"
              label="Email"
              v-model="email"
              :disabled="loading"
              prepend-icon="email"
              @input="checkDuplicateEmail"
              :rules="[$vfRule('required'), $vfRule('email'), $vfRule('duplicateEmail', undefined, duplicateEmail)]"
              required
            />

            <v-select
              :items="types"
              :disabled="loading"
              v-model="type"
              label="Employer or Employee?"
              single-line
              :prepend-icon="type ? type.icon : 'verified_user'"
              return-object
              bottom
              :rules="[$vfRule('required')]"
              required
            >
              <template slot="item" slot-scope="data">
                
                <template v-if="typeof data.item !== 'object'">
                  <v-list-tile-content v-text="data.item"></v-list-tile-content>
                </template>

                <template v-else>
                  <v-list-tile-avatar>
                    <v-icon
                      :color="type && type.value == data.item.value ? 'primary' : null"
                    >{{ data.item.icon }}</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-content>
                    <v-list-tile-title v-text="data.item.text"></v-list-tile-title>
                  </v-list-tile-content>
                </template>

              </template>
            </v-select>

            <v-subheader>Birthdate</v-subheader>
            <birthdate-picker
              required
              v-model="birthdate"
            />

            <v-subheader>Account Access</v-subheader>
            <v-text-field
              label="Password"
              v-model="password"
              prepend-icon="lock"
              :disabled="loading"
              :type="hidePass.password ? 'password' : 'text'"
              :append-icon="hidePass.password ? 'visibility' : 'visibility_off'"
              :append-icon-cb="() => (hidePass.password = !hidePass.password)"
              hint="Password must be at least 8 characters!"
              :rules="[$vfRule('required'), $vfRule('chars8')]"
              required
            />

            <v-text-field
              label="Confirm Password"
              v-model="passconf"
              prepend-icon="lock_outline"
              :disabled="loading"
              :type="hidePass.passconf ? 'password' : 'text'"
              :append-icon="hidePass.passconf ? 'visibility' : 'visibility_off'"
              :append-icon-cb="() => (hidePass.passconf = !hidePass.passconf)"
              :rules="[$vfRule('match', undefined, password)]"
            />

          </v-form>
          
          <v-btn
            color="primary"
            @click.native="checkDuplicateEmail(email, true)"
            :disabled="loading || !form"
          >Create Account</v-btn>
        </v-stepper-content>


        <v-stepper-step step="2" :complete="stepper > 2">Account verification</v-stepper-step>

        <v-stepper-content step="2">
          
          <div>
            <h4 class="display-1 mb-3">Welcome {{ fname + ' ' + lname }}!</h4>
            <p class="grey--text text--darken-1">
              <template>An email has been sent to</template>
              <strong v-text="email"/>
              <template>for verification.</template>
            </p>
            <p
              class="grey--text text--darken-1"
            >Check out cool stuff by going to our dashboard!</p>
          </div>

          <v-btn
            to="/dashboard"
            color="primary"
          >Go to Dashboard</v-btn>
        </v-stepper-content>

      </v-stepper-items>
    </v-stepper>
  </v-container>

  </v-flex>

</v-layout>
</template>

<script>
import qs from 'qs'
import BirthdatePicker from '@/include/BirthdatePicker'

export default {
  name: 'sign-up',
  components: {
    BirthdatePicker
  },
  data: () => ({
    url: '/users/signup',
    form: false,
    stepper: 1,
    fname: null,
    lname: null,
    email: null,
    type: null,
    birthdate: null,
    password: null,
    passconf: null,
    hidePass: {
      password: true,
      passconf: true
    },
    types: [
      { text: 'Employer', icon: 'person', value: 3 },
      { text: 'Employee', icon: 'person_outline', value: 4 }
    ],
    loading: false,
    duplicateEmail: false
  }),

  methods: {
    formNext() {
      if (!this.$refs.form.validate()) {
        return
      }
      this.stepper = 2
    },

    checkDuplicateEmail(e, doSubmit) {
      // reset
      this.duplicateEmail = false

      // if doSubmit is set
      doSubmit = typeof doSubmit === 'boolean' && doSubmit === true
      if (doSubmit && !this.$refs.form.validate()) {
        return
      }

      // only check if validated
      if (this.$vfRule('email')(e) != true) {
        return
      }

      if (doSubmit) {
        this.loading = true
      }

      // check if exists
      this.$http.post('/users/duplicateEmailCheck', qs.stringify({
        email: e
      })).then((res) => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.duplicateEmail = res.data.exists
        if (typeof doSubmit === 'boolean' && doSubmit === true) {
          this.submit()
        }
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },

    submit() {
      if (!this.$refs.form.validate()) {
        this.loading = false
        return
      }
      this.loading = true

      // create formdata
      let data = new FormData()
      data.append('email', this.email)
      data.append('fname', this.fname)
      data.append('lname', this.lname)
      data.append('birthdate', JSON.stringify(this.birthdate))
      data.append('password', this.password)
      data.append('type', this.type.value)

      let msg = 'Unable to create account.'

      this.$http.post(this.url, data).then((res) => {
        console.warn(res.data)
        if (!res.data.success) {
          if (res.data.error) {
            msg = res.data.error
          }
          throw new Error('Request failure.')
        }
        this.loading = false
        this.stepper = 2
      }).catch(e => {
        console.error(e)
        this.$bus.$emit('snackbar--show', {
          text: msg,
          btns: {
            text: 'Retry',
            icon: false,
            color: 'accent',
            cb: (sb, e) => {
              this.submit()
              sb.snackbar = false
            }
          }
        })
        this.loading = false
      })
    },
  }
}
</script>
