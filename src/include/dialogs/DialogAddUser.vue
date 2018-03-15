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

      <v-form
        class="px-4"
        ref="form"
        v-model="formValid"
        enctype="multipart/form-data"
      >

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

        <v-layout row v-if="!userMode">
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

        <v-layout>
          <v-flex hidden-xs-only sm4>
            <v-subheader>
              <div>
                <div>Profile image</div>
                <div class="caption" v-text="'(jpg, png)'"/>
              </div>
            </v-subheader>
          </v-flex>
          <v-flex sm8 class="py-2 mt-1">
            <v-icon class="pr-2 mr-1">insert_photo</v-icon>
            <input
              type="file"
              :disabled="loading"
              @change="filesChange"
              :name="fileName"
            />
            <v-layout
              v-if="imgSrc"
              class="mt-3"
            >
              <v-avatar
                tile
                size="128"
                class="elevation-1"
              >
                <img :src="file ? imgSrc : $wrap.localImg(imgSrc)"/>
              </v-avatar>
              <div class="pa-2 caption">
                <div>
                  <strong>
                    <template v-if="file">Image Preview</template>
                    <template v-else>Existing Image</template>
                  </strong>
                </div>
                <template v-if="file">
                  <div v-text="file.name"/>
                  <div v-text="$wrap.fileSize(file.size)"/>
                </template>
              </div>
            </v-layout>
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
        @click="$bus.dialog.ManageUsers.add = false">Cancel</v-btn>
      <v-btn
        flat
        tabindex="0"
        color="primary"
        :disabled="loading"
        v-if="mode !== 'Edit'"
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
    },
    userMode: {
      type: Boolean,
      default: false
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
    duplicateEmail: false,
    fileName: 'file',
    file: null
  }),
  watch: {
    mode(to, from) {
      if (to === 'Edit') {
        if (typeof this.doEdit === 'function') {
          this.doEdit()
        }
      }
    },
    userToEdit(e) {
      if (e !== null) {
        this.doEdit()
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
    filesChange(e) {
      let f = e.target
      if (!(f.files && f.files.item(0))) {
        this.file = null
        return
      }
      this.file = f.files.item(0)

      let reader = new FileReader()

      reader.onload = (e) => {
        this.imgSrc = e.target.result
      }
      reader.readAsDataURL(this.file)
    },

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
      if (!this.userToEdit) {
        return
      }

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
          // parse it if string
          if (typeof val === 'string') {
            val = JSON.parse(val)
          }
        }

        // if e is type
        if (e == 'type') {
          val = this.types[val - 2]
        }

        // if status
        if (e == 'status') {
          val = val == 1 || val == 2
        }

        if (e == 'img_src') {
          e = 'imgSrc'
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

      // create formdata
      let data = new FormData()
      data.append('email', this.email)
      data.append('fname', this.fname)
      data.append('lname', this.lname)
      data.append('bio', this.bio)
      data.append('birthdate', JSON.stringify(this.birthdate))

      // if password is set, use password
      data.append('alsoPassword', this.alsoPassword)
      data.append('password', this.password)
      data.append('type', this.type.value)
      data.append('contact', this.contact)
      data.append('status', this.status ? 1 : 0)

      data.append('places', JSON.stringify(this.places))
      data.append('job_tags', JSON.stringify(this.job_tags))
      data.append('socials', JSON.stringify(this.socials))

      data.append('id', this.id)
      data.append('mode', this.mode)
      data.append('settings', JSON.stringify(this.settings))

      if (this.file) {
        data.append('file', this.file)
      }

      this.$http.post(this.url, data).then((res) => {
        console.warn(res.data)
        if (!res.data.success) {
          if (res.data.error) {
            this.$bus.$emit('snackbar--show', res.data.error)
          }
          throw new Error('Request failure.')
        }
        this.loading = false
        this.clear()
        this.$bus.dialog.ManageUsers.add = false
        
        let msg = this.mode == 'Add' ? 'Added user successfully.' : 'Updated user information.'
        this.$bus.$emit('snackbar--show', msg)
        this.$bus.$emit('update--manage-users')
        if (this.$route.name == 'Profile') {
          this.$bus.sessionCheck(this.$route, this.$http)
        }
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },
    clear() {
      if (this.$refs.form) {
        this.$refs.form.reset()
        if (typeof this.$refs.form.$el.reset === 'function') {
          this.$refs.form.$el.reset()
        }
      }
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
      this.file = null
      this.imgSrc = null
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
