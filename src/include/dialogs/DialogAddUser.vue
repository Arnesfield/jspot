<template>
<v-dialog
  v-model="$bus.dialog.ManageUsers.add"
  transition="fade-transition"
  scrollable
  :persistent="true"
  max-width="900"
>
  <v-card>

    <!-- toolbar -->
    
    <v-toolbar dark flat color="primary lighten-1">
      <v-btn
        dark
        icon
        :disabled="loading"
        @click.native="$bus.dialog.ManageUsers.add = false"
      >
        <v-icon>close</v-icon>
      </v-btn>
      <v-toolbar-title>{{ mode }} User</v-toolbar-title>
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
              @input="checkDuplicateEmail"
              :rules="[$vfRule('required'), $vfRule('email'), $vfRule('duplicateEmail', undefined, duplicateEmail)]"
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
            <v-text-field
              label="Contact"
              v-model="contact"
              :disabled="loading"
              prepend-icon="phone"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Birthdate</v-subheader>
          </v-flex>
          <v-flex sm8>
            <birthdate-picker
              required
              v-model="birthdate"
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
              v-model="places"
            />
            <select-job-tags
              :disabled="loading"
              v-model="job_tags"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Socials</v-subheader>
          </v-flex>
          <v-flex sm8>
            <social-links
              v-model="socials"
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
              :disabled="mode == 'Edit' ? loading : true"
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
              :label="'Status: ' + $wrap.status(status)"
              v-model="status"/>
          </v-flex>
        </v-layout>
      </v-form>

    </v-card-text>

    <!-- end of content -->

    <v-card-actions class="pa-4 bg-dim">
      <dialog-loading :loading="loading"/>
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
        @click="checkDuplicateEmail(email, true)">{{ mode }}</v-btn>
    </v-card-actions>

  </v-card>
</v-dialog>
</template>

<script>
import BirthdatePicker from '@/include/BirthdatePicker'
import SocialLinks from '@/include/SocialLinks'
import SelectPlaces from '@/include/SelectPlaces'
import SelectJobTags from '@/include/SelectJobTags'
import DialogLoading from '@/include/DialogLoading'
import qs from 'qs'

export default {
  name: 'dialog-add-user',
  components: {
    BirthdatePicker,
    SocialLinks,
    SelectPlaces,
    DialogLoading,
    SelectJobTags
  },
  props: {
    mode: {
      type: String,
      default: 'Add'
    },
    userToEdit: {
      type: Object,
      default: null
    }
  },
  data: () => ({
    url: '/users/add',
    formValid: false,
    id: null,
    email: null,
    fname: null,
    lname: null,
    bio: null,
    birthdate: null,
    password: null,
    passconf: null,
    imgSrc: null,
    type: null,
    contact: null,
    status: true,
    hidePass: {
      password: true,
      passconf: true
    },
    types: [
      { text: 'Admin', icon: 'verified_user', value: 2 },
      { text: 'Employer', icon: 'person', value: 3 },
      { text: 'Employee', icon: 'person_outline', value: 4 }
    ],
    places: [],
    job_tags: [],
    socials: [],
    settings: {},

    loading: false,
    alsoPassword: false,
    duplicateEmail: false
  }),
  watch: {
    mode(to, from) {
      if (to === 'Edit') {
        if (typeof this.doEdit === 'function') {
          this.doEdit()
        }
      }
    }
  },

  created() {
    this.$bus.$on('dialog--manage-user.add', this.manageUserAdd)
    this.alsoPassword = this.mode == 'Add'
  },
  beforeDestroy() {
    this.$bus.$off('dialog--manage-user.add', this.manageUserAdd)
  },

  methods: {
    manageUserAdd(to, from) {
      this.alsoPassword = this.mode == 'Add'
      if (to && this.mode == 'Edit') {
        this.doEdit()
      }
      if (!to) {
        this.clear()
      }
    },
    doEdit() {
      this.alsoPassword = this.mode == 'Add'

      const parseFields = ['places', 'socials', 'job_tags', 'settings']
      // set user values
      Object.keys(this.userToEdit).forEach(e => {
        // skip password
        if (e == 'password') { return }

        // change if e is in parseFields
        // if yes, parse that json
        let val = this.userToEdit[e]
        if (parseFields.includes(e)) {
          val = JSON.parse(val)
        }

        // if e is type
        if (e == 'type') {
          val = this.types[val - 2]
        }

        // if status
        if (e == 'status') {
          val = val == 1
        }

        this[e] = val
      })
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
        id: this.id,
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
      this.$http.post(this.url, qs.stringify({
        email: this.email,
        fname: this.fname,
        lname: this.lname,
        bio: this.bio,
        birthdate: this.birthdate,
        // if password is set, use password
        alsoPassword: this.alsoPassword,
        password: this.password,
        img_src: this.imgSrc,
        type: this.type.value,
        contact: this.contact,
        status: this.status ? 1 : 0,

        places: this.places,
        job_tags: this.job_tags,
        socials: this.socials,

        id: this.id,
        mode: this.mode,
        settings: this.settings
      })).then((res) => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.clear()
        this.$bus.dialog.ManageUsers.add = false
        
        let msg = this.mode == 'Add' ? 'Added user successfully.' : 'Updated user information.'
        this.$bus.$emit('snackbar--show', msg)
        this.$bus.$emit('update--manage-users')
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },
    clear() {
      this.$refs.form.reset()
      this.id = null
      this.email = null
      this.fname = null
      this.lname = null
      this.bio = null
      this.birthdate = null
      this.password = null
      this.passconf = null
      this.type = null
      this.contact = null
      this.status = true
      this.hidePass.password = true
      this.hidePass.passconf = true
      this.alsoPassword = this.mode == 'Add'
      this.places = []
      this.job_tags = []
      this.socials = []
      this.duplicateEmail = false
    }
  }
}
</script>
