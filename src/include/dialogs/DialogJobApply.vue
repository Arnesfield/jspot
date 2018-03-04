<template>
<v-dialog
  v-model="show"
  transition="fade-transition"
  scrollable
  :persistent="loading"
  max-width="640"
>
  <v-card v-if="job">

    <!-- toolbar -->
    <!-- slot="extension" -->
    <v-progress-linear
      color="warning"
      height="3"
      :active="loading"
      indeterminate
      class="ma-0 primary lighten-1"
    />
    <v-layout class="primary lighten-1" style="min-height: 48px">
      <v-btn
        dark
        icon
        :disabled="loading"
        @click.native="show = false"
        @keypress.enter="show = false"
      >
        <v-icon>close</v-icon>
      </v-btn>
      <v-tabs
        dark
        v-model="tabs"
        color="primary lighten-1"
      >
        <v-tab :disabled="loading">Info</v-tab>
        <v-tab :disabled="loading" >Apply</v-tab>
      </v-tabs>
    </v-layout>
    <!-- end of toolbar -->

    <!-- content -->

    <v-card-text>

      <v-tabs-items v-model="tabs">
        <v-tab-item>
          <job-details :job="job"/>
        </v-tab-item>
        <v-tab-item>
          <apply-form
            ref="form"
            class="px-4"
            :load="loading"
            @loading="(e) => { loading = e }"
            @close="onClose"
          />
        </v-tab-item>
      </v-tabs-items>

    </v-card-text>

    <!-- end of content -->

    <v-divider/>
    <v-card-actions class="px-2">
      <v-spacer/>
      <v-btn
        flat
        tabindex="0"
        v-if="tabs == '0'"
        :disabled="loading"
        @click="show = false"
        @keypress.enter="show = false"
        v-text="'Cancel'"
      />
      <v-btn
        flat
        tabindex="0"
        v-if="tabs == '1'"
        :disabled="loading"
        @click="tabs = '0'"
        @keypress.enter="tabs = '0'"
        v-text="'Previous'"
      />
      <v-btn
        :flat="tabs == '0'"
        color="primary"
        tabindex="0"
        :disabled="loading"
        @click="clickPrimary"
        @keypress.enter="clickPrimary"
        v-text="tabs == '0' ? 'Next' : 'Apply'"
      />
    </v-card-actions>

  </v-card>
</v-dialog>
</template>

<script>
import ApplyForm from '@/include/forms/ApplyForm'
import JobDetails from '@/include/JobDetails'
import DialogLoading from '@/include/DialogLoading'

export default {
  name: 'dialog-job-opening',
  components: {
    ApplyForm,
    JobDetails,
    DialogLoading
  },
  data: () => ({
    url: '/jobs/apply',
    show: false,
    job: null,

    tabs: '0',
    formValid: false,

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
    this.$bus.$on('dialog--job.apply', (job) => {
      this.job = job
      this.show = true
    })
  },

  methods: {
    clickPrimary() {
      // check tabs
      if (this.tabs == '0') {
        this.tabs = '1'
        return
      }
      this.submit()
    },
    onClose() {
      this.show = false
      this.$emit('success')
    },

    submit() {
      this.$refs.form.apply(this.job)
    },
    clear() {
      if (this.$refs.form) {
        this.$refs.form.clear()
      }

      this.job = null
      this.tabs = '0'
      this.loading = false
    }
  }
}
</script>
