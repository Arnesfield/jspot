<template>
<v-container
  grid-list-lg
  :style="!applications.length ? {
    'height': 'calc(100% - 64px)',
    'display': 'flex'
  } : null"
>
  <template v-if="applications.length">
    <v-list
      three-line
      class="elevation-1"
    >
      <template v-for="(item, i) in applications">
        <job-application-inst
          :key="'apply-' + i"
          :item='item'
          @view="viewJob"
        />
        <v-divider
          :key="'divider-' + i"
          v-if="applications.length-1 != i"
        />
      </template>
    </v-list>
  </template>

  <template v-else>
    <v-layout justify-center align-center>
      <manage-no-data
        :loading="loading"
        :fetch="fetch"
        msg="You have not made any job applications :("
      >
        <div slot="icon" class="mb-3">
          <v-icon size="64px">work</v-icon>
        </div>
      </manage-no-data>
    </v-layout>
  </template>

  <dialog-job-apply
    ref="dialogJobApply"
    @delete="deleteApply"
  />

</v-container>
</template>

<script>
import qs from 'qs'
import DialogJobApply from '@/include/dialogs/DialogJobApply'
import JobApplicationInst from '@/include/JobApplicationInst'
import ManageNoData from '@/include/ManageNoData'

export default {
  name: 'my-job-applications',
  components: {
    DialogJobApply,
    JobApplicationInst,
    ManageNoData
  },
  data: () => ({
    url: '/apply/my',
    applications: [],
    loading: false
  }),

  created() {
    this.fetch()
  },

  methods: {
    deleteApply(job) {
      this.$bus.$emit('dialog--delete.show', {
        item: job,
        title: 'Delete job application',
        subtitle: 'Job title: <strong>' + job.title + '</strong>',
        msg: '<div class="body-1">This will remove your job application.</div><div><strong>Note: This will not delete the email sent to this employer.</strong></div>',
        fn: (onSuccess, onError, close, fn) => {
          this.$http.post('/apply/delete', qs.stringify({
            id: job.a_id
          })).then((res) => {
            console.warn(res.data)
            if (!res.data.success) {
              throw new Error('Request failure.')
            }
            this.$bus.$emit('snackbar--show', 'Removed job application.')
            if (this.$refs.dialogJobApply) {
              this.$refs.dialogJobApply.close()
            }
            // update applications
            this.fetch()
            onSuccess()
          }).catch(e => {
            console.error(e)
            this.$bus.$emit('snackbar--show', {
              text: 'Unable to remove job application.',
              btns: {
                text: 'Retry',
                icon: false,
                color: 'accent',
                cb: (sb, e) => {
                  fn(onSuccess, onError, close, fn)
                  // this.deleteApply(job)
                  sb.snackbar = false
                }
              }
            })
            onError()
            close()
          })
        }
      })
    },

    viewJob(job) {
      this.$bus.$emit('dialog--job.apply', job, true, true)
    },

    fetch() {
      this.loading = true
      this.$http.post(this.url).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure')
        }

        this.applications = res.data.applications
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
