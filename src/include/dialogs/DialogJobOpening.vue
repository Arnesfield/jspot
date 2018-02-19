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
              v-model="about"
              :disabled="loading"
              prepend-icon="subject"
              multi-line
              class="multi-line-textarea"
            />
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex hidden-xs-only sm4>
            <v-subheader>Demographic</v-subheader>
          </v-flex>
          <v-flex sm8>
            <age-group-picker v-model="age"/>
            <select-places
              :disabled="loading"
              @update-places="(e) => { places = e }"
            />
            <select-job-tags
              :disabled="loading"
              label="Related job tags"
              @update-job-tags="(e) => { job_tags = e }"
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
import qs from 'qs'

export default {
  name: 'dialog-job-opening',
  components: {
    AgeGroupPicker,
    SelectPlaces,
    SelectJobTags,
    DialogLoading
  },
  data: () => ({
    show: false,
    mode: 'Create',

    formValid: false,
    title: null,
    age: null,
    about: null,
    places: [],
    job_tags: [],
    birthdate: null,

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
    },
    clear() {
      if (this.$refs.form) {
        this.$refs.form.reset()
      }

      this.mode = 'Create'
      this.formValid = false
      this.title = null
      this.about = null
      this.places = []
      this.job_tags = []
      this.loading = false
    }
  }
}
</script>
