<template>
<v-dialog
  v-model="show"
  transition="fade-transition"
  scrollable
  :persistent="loading"
  max-width="640"
>
  <v-card v-if="job">
    <v-toolbar
      dark
      card
      dense
      color="primary lighten-1"
    >
      <v-btn
        icon
        @click="show = false"
      >
        <v-icon>close</v-icon>
      </v-btn>
      <v-toolbar-title
        v-text="'Application'"
      />
    </v-toolbar>

    <v-progress-linear
      color="warning"
      height="3"
      :active="loading"
      indeterminate
      background-color="primary lighten-1"
      class="ma-0 primary lighten-1"
    />

    <v-card-text>
      <apply-details :item="job"/>
      <v-container>
        <v-form
          ref="form"
          v-model="formValid"
          lazy-validation
        >
          <schedule-date-picker v-model="date" required/>
          <schedule-time-picker v-model="time" required/>
        </v-form>
      </v-container>
    </v-card-text>

    <v-divider/>
    <v-card-actions>
      <div style="margin: 0 auto">
        <v-btn
          flat
          color="error"
          :disabled="loading"
          @click="doAction(0)"
          @keypress.enter="doAction(0)"
        >
          <v-icon>cancel</v-icon>
          <span>Deny</span>
        </v-btn>
        <v-tooltip top>
          <v-btn
            flat
            slot="activator"
            color="grey"
            :disabled="loading"
            @click="doAction(1)"
            @keypress.enter="doAction(1)"
          >
            <v-icon>offline_pin</v-icon>
            <span>Pending</span>
          </v-btn>
          <span>Set to pending</span>
        </v-tooltip>
        <v-tooltip top>
          <v-btn
            flat
            slot="activator"
            color="primary"
            :disabled="loading"
            @click="acceptApply"
            @keypress.enter="acceptApply"
          >
            <v-icon>offline_pin</v-icon>
            <span>Accept</span>
          </v-btn>
          <span>Set interview schedule</span>
        </v-tooltip>
        <v-tooltip top>
          <v-btn
            flat
            slot="activator"
            color="success"
            :disabled="loading"
            @click="doAction(3)"
            @keypress.enter="doAction(3)"
          >
            <v-icon>check_circle</v-icon>
            <span>Hire</span>
          </v-btn>
          <span>No interview</span>
        </v-tooltip>
      </div>
    </v-card-actions>
  </v-card>

</v-dialog>
</template>

<script>
import qs from 'qs'
import ScheduleDatePicker from '@/include/ScheduleDatePicker'
import ScheduleTimePicker from '@/include/ScheduleTimePicker'
import ApplyDetails from '@/include/ApplyDetails'

export default {
  name: 'dialog-apply-action',
  components: {
    ScheduleDatePicker,
    ScheduleTimePicker,
    ApplyDetails
  },
  data: () => ({
    url: '/apply/update',
    show: false,
    loading: false,
    job: null,
    formValid: false,
    // date and time picker
    date: null,
    time: null
  }),
  watch: {
    show(e) {
      if (!e) {
        this.clear()
      }
    }
  },
  created() {
    this.$bus.$on('dialog--apply-action.show', this.applyActionShow)
  },
  beforeDestroy() {
    this.$bus.$off('dialog--apply-action.show', this.applyActionShow)
  },

  methods: {
    applyActionShow(job) {
      this.job = job
      this.show = true
    },
    clear() {
      this.job = null
      this.date = null
      this.time = null
      this.loading = false
    },

    acceptApply() {
      if (this.$refs.form) {
        if (this.$refs.form.validate()) {
          this.doAction(2)
        }
      }
    },

    doAction(e) {
      this.loading = true
      this.$http.post(this.url, qs.stringify({
        id: this.job.a_id,
        date: this.date,
        time: this.time,
        status: e
      })).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }

        let msg = ''
        if (e == 0) {
          msg = 'Application denied successfully.'
        } else if (e == 1) {
          msg = 'Set to pending successfully.'
        } else if (e == 2) {
          msg = 'Application accepted successfully.'
        } else if (e == 3) {
          msg = 'Application hired successfully.'
        }

        // update applicants
        this.$bus.$emit('dialog--job-apply.update-applicants')
        this.$bus.$emit('snackbar--show', msg)
        this.show = false
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
