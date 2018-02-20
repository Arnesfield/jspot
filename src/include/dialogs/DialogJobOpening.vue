<template>
<v-dialog
  v-model="show"
  transition="fade-transition"
  scrollable
  :persistent="loading"
  max-width="900"
>
  <v-card>

    <!-- toolbar -->
    
    <v-toolbar dark flat color="primary">
      <v-btn
        dark
        icon
        :disabled="loading"
        @click.native="show = false"
      >
        <v-icon>close</v-icon>
      </v-btn>
      <v-toolbar-title>{{ mode }} job opening</v-toolbar-title>
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
            <v-text-field
              label="Title of job"
              v-model="title"
              :disabled="loading"
              prepend-icon="title"
              :rules="[$vfRule('required')]"
              required
            />
            <v-text-field
              label="Brief description"
              v-model="description"
              :disabled="loading"
              prepend-icon="subject"
              multi-line
              class="multi-line-textarea"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Date from</v-subheader>
          </v-flex>
          <v-flex sm8>
            <birthdate-picker
              required
              v-model="date.from"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Date until</v-subheader>
          </v-flex>
          <v-flex sm8>
            <birthdate-picker
              required
              v-model="date.to"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Time</v-subheader>
          </v-flex>
          <v-flex sm8>
            <time-from-to-picker
              required
              v-model="time"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Demographic</v-subheader>
          </v-flex>
          <v-flex sm8>
            <age-group-picker
              required
              v-model="age"
            />
            <select-places
              :disabled="loading"
              label="Location"
              placeholder="Where is this located?"
              v-model="location"
            />
            <select-job-tags
              :disabled="loading"
              label="Job tags"
              placeholder="Any related job tags? (e.g. programming, art, etc.)"
              v-model="job_tags"
            />
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
        @click="submit">{{ mode }}</v-btn>
    </v-card-actions>

  </v-card>
</v-dialog>
</template>

<script>
import AgeGroupPicker from '@/include/AgeGroupPicker'
import SelectPlaces from '@/include/SelectPlaces'
import SelectJobTags from '@/include/SelectJobTags'
import DialogLoading from '@/include/DialogLoading'
import TimeFromToPicker from '@/include/TimeFromToPicker'
import BirthdatePicker from '@/include/BirthdatePicker'
import qs from 'qs'

export default {
  name: 'dialog-job-opening',
  components: {
    AgeGroupPicker,
    SelectPlaces,
    SelectJobTags,
    DialogLoading,
    TimeFromToPicker,
    BirthdatePicker
  },
  data: () => ({
    addUrl: '/jobs/add',
    updateUrl: '/jobs/update',
    show: false,
    mode: 'Create',

    formValid: false,
    title: null,
    age: null,
    description: null,
    location: [],
    job_tags: [],
    time: null,
    date: {
      from: null,
      to: null
    },

    loading: false
  }),

  watch: {
    show(to, from) {
      // if exit, clear
      if (!to) {
        this.clear()
      }
    }
  },

  created() {
    this.$bus.$on('dialog--job-opening.add', (mode) => {
      if (typeof mode !== 'string') {
        this.mode = 'Create'
      }
      this.show = true
    })
  },

  methods: {
    submit() {
      if (!this.$refs.form.validate()) {
        this.loading = false
        return
      }

      // check mode
      let url = ''
      this.loading = true
      if (this.mode == 'Create') {
        url = this.addUrl
      } else if (this.mode == 'Update') {
        url = this.updateUrl
      }

      this.$http.post(url, qs.stringify({
        mode: this.mode,
        title: this.title,
        description: this.description,
        age: JSON.stringify(this.age),
        timeFrom: this.time.from + ':00',
        timeTo: this.time.to + ':00',
        dateFrom: this.date.from,
        dateTo: this.date.to,
        location: JSON.stringify(this.location),
        job_tags: JSON.stringify(this.job_tags)
      })).then(res => {
        console.error(res)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        // show message
        let msg = ''
        if (this.mode == 'Create') {
          msg = 'Job opening created.'
        } else if (this.mode == 'Create') {
          msg = 'Job opening updated.'
        }
        this.show = false
        this.loading = false
        this.$bus.$emit('snackbar--show', msg)
        this.$bus.$emit('update--my-job-openings');
        this.clear()
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },
    clear() {
      if (this.$refs.form) {
        this.$refs.form.reset()
      }

      this.mode = 'Create'
      this.formValid = false
      this.title = null
      this.description = null
      this.location = []
      this.job_tags = []
      this.time = null
      this.date.from = null
      this.date.to = null
      this.loading = false
    }
  }
}
</script>
