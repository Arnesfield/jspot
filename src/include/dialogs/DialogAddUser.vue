<template>
<v-dialog
  v-model="$bus.dialog[$route.path]"
  transition="fade-transition"
  scrollable
  :persistent="loading"
  max-width="900"
>
  <v-card>

    <!-- toolbar -->
    
    <v-toolbar dark color="primary">
      <v-btn
        dark
        icon
        :disabled="loading"
        @click.native="() => { $bus.dialog[$route.path] = false; clear() }"
      >
        <v-icon>close</v-icon>
      </v-btn>
      <v-toolbar-title>Add User</v-toolbar-title>
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
              :rules="[$vfRule('required'), $vfRule('email')]"
              required
            />
            <v-text-field
              label="Bio"
              v-model="bio"
              :disabled="loading"
              prepend-icon="subject"
              multi-line
              class="multi-line-textarea"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Interested In</v-subheader>
          </v-flex>
          <v-flex sm8>
            <select-places
              :disabled="loading"
              @update-places="(e) => { places = e }"
            />
            <select-job-tags
              :disabled="loading"
              @update-job-tags="(e) => { jobTags = e }"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Socials</v-subheader>
          </v-flex>
          <v-flex sm8>
            <social-links
              :socials.sync="socials"
              :disabled="loading"
            />
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
              :disabled="mode == 'edit' ? loading : true"
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
        tabindex="0"
        :disabled="loading"
        @click="clear">Clear</v-btn>
      <v-btn
        color="primary"
        tabindex="0"
        :disabled="loading"
        @click="submit">Add</v-btn>
    </v-card-actions>

  </v-card>
</v-dialog>
</template>

<script>  
import SocialLinks from '@/include/SocialLinks'
import SelectPlaces from '@/include/SelectPlaces'
import SelectJobTags from '@/include/SelectJobTags'
import qs from 'qs'
import wrap from '@/assets/js/wrap'

export default {
  name: 'dialog-add-user',
  components: {
    SocialLinks,
    SelectPlaces,
    SelectJobTags
  },
  props: {
    mode: {
      type: String,
      default: 'add'
    }
  },
  data: () => ({
    url: '/users/add',
    formValid: false,
    email: null,
    fname: null,
    lname: null,
    bio: null,
    password: null,
    passconf: null,
    imgSrc: null,
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
    places: [],
    jobTags: [],
    socials: [],

    loading: false,
    alsoPassword: false
  }),
  computed: {
    wrap: () => wrap
  },

  created() {
    this.alsoPassword = this.mode == 'add'
  },

  methods: {
    submit() {
      if (!this.$refs.form.validate()) {
        return
      }
      this.loading = true
      this.$http.post(this.url, qs.stringify({
        email: this.email,
        fname: this.fname,
        lname: this.lname,
        bio: this.bio,
        // if password is set, use password
        alsoPassword: this.alsoPassword,
        password: this.password,
        img_src: this.imgSrc,
        type: this.type.value,
        status: this.status ? 1 : 0,

        places: this.places,
        job_tags: this.jobTags,
        socials: this.socials
      })).then((res) => {
        console.error(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.clear()
        this.$bus.dialog[this.$route.path] = false
        this.$bus.$emit('update--manage-users')
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },
    clear() {
      this.$refs.form.reset()
      this.email = null
      this.fname = null
      this.lname = null
      this.bio = null
      this.password = null
      this.passconf = null
      this.type = null
      this.status = true
      this.hidePass.password = true
      this.hidePass.passconf = true
      this.alsoPassword = this.mode == 'add'
      this.places = []
      this.jobTags = []
      this.socials = []
    }
  }
}
</script>
