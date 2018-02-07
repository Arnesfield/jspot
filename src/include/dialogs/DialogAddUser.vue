<template>
<v-dialog
  v-model="$bus.dialog[$route.path]"
  transition="fade-transition"
  scrollable
  :persistent="loading"
  max-width="720"
>
  <v-card>

    <!-- toolbar -->
    
    <v-toolbar dark color="primary">
      <v-btn
        dark
        icon
        :disabled="loading"
        @click.native="$bus.dialog[$route.path] = false"
      >
        <v-icon>close</v-icon>
      </v-btn>
      <v-toolbar-title>Add Users</v-toolbar-title>
    </v-toolbar>

    <!-- end of toolbar -->

    <!-- content -->

    <v-card-text>

      <v-form class="px-4" ref="form" v-model="formValid">

        <v-layout>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Basic Information</v-subheader>
          </v-flex>
          <v-flex sm8>
            <v-layout row>
              <v-flex xs12 sm6>
                <v-text-field
                  label="First name"
                  v-model="fname"
                  :disabled="loading"
                  prepend-icon="account_circle"
                  required/>
              </v-flex>
              &nbsp;
              <v-flex xs12 sm6>
                <v-text-field
                  label="Last name"
                  v-model="lname"
                  :disabled="loading"
                  required/>
              </v-flex>
            </v-layout>
            <v-text-field
              type="email"
              label="Email"
              v-model="email"
              :disabled="loading"
              prepend-icon="email"
              required/>
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Account Access</v-subheader>
          </v-flex>
          <v-flex sm8>
            <v-checkbox
              class="mt-2"
              label="Change password"
              :disabled="loading"
              :hint="alsoPassword ? 'Note: Current password will be overwritten!' : undefined"
              persistent-hint
              v-model="alsoPassword"
            />

            <template v-if="alsoPassword">
              <v-text-field
                label="Password"
                v-model="password"
                prepend-icon="lock"
                :disabled="loading"
                :type="hidePass.password ? 'password' : 'text'"
                :append-icon="hidePass.password ? 'visibility' : 'visibility_off'"
                :append-icon-cb="() => (hidePass.password = !hidePass.password)"
                hint="Password must be at least 8 characters!"
                required/>

              <v-text-field
                label="Confirm Password"
                v-model="passconf"
                prepend-icon="lock_outline"
                :disabled="loading"
                :type="hidePass.passconf ? 'password' : 'text'"
                :append-icon="hidePass.passconf ? 'visibility' : 'visibility_off'"
                :append-icon-cb="() => (hidePass.passconf = !hidePass.passconf)"
                required/>
            </template>

          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Account Status</v-subheader>
          </v-flex>
          <v-flex sm8>
            <v-select
              :items="types"
              :disabled="loading"
              v-model="type"
              label="Type"
              single-line
              :prepend-icon="type ? type.icon : 'verified_user'"
              return-object
              bottom
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
            <v-switch
              :disabled="loading"
              :label="'Status: ' + wrap.status(status)"
              v-model="status"/>
          </v-flex>
        </v-layout>
      </v-form>

    </v-card-text>

    <!-- end of content -->

    <v-card-actions class="px-4 py-4 grey lighten-3">
      <template v-if="loading">
        <v-progress-circular
          indeterminate
          :active="loading"
          color="grey"
        />
        <span style="height: auto" class="subheader px-2">Loading...</span>
      </template>
      <v-spacer/>
      <v-btn
        flat
        :disabled="loading"
        @click="clear">Clear</v-btn>
      <v-btn
        color="primary"
        :disabled="loading"
        @click="submit">Add</v-btn>
    </v-card-actions>

  </v-card>
</v-dialog>
</template>

<script>
import qs from 'qs'
import wrap from '@/assets/js/wrap'

export default {
  name: 'dialog-add-user',
  data: () => ({
    url: '/users/add',
    formValid: false,
    email: null,
    fname: null,
    lname: null,
    password: null,
    passconf: null,
    img_src: null,
    type: null,
    status: true,
    hidePass: {
      password: true,
      passconf: true
    },
    types: [
      { text: 'Admin', icon: 'verified_user', value: 2 },
      { text: 'Normal', icon: 'person', value: 3 }
    ],

    loading: false,
    alsoPassword: false
  }),
  computed: {
    wrap: () => wrap
  },

  methods: {
    submit() {
      this.loading = true
      console.log(this.$refs.form.validate())
    },
    clear() {
      this.$refs.form.reset()
      this.fname = null
      this.lname = null
      this.email = null
      this.password = null
      this.passconf = null
      this.type = null
      this.status = true
      this.hidePass.password = true
      this.hidePass.passconf = true
      this.alsoPassword = false
    }
  }
}
</script>
