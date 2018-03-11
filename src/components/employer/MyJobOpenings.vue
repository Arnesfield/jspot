<template>
<v-container
  :style="!jobs.length ? {
    'height': 'calc(100% - 64px)',
    'display': 'flex'
  } : null"
  v-bind="{ ['grid-list-' + size]: true }"
>
  <template v-for="(t, i) in types" v-if="countItems(t.n)">
    <v-subheader :key="'subheader-' + i">{{ t.title }}</v-subheader>
    <v-layout :key="'layout-' + i" row wrap>
      <v-flex
        xs12
        sm12
        md6
        lg4
        :key="i"
        v-for="(job, i) in jobs"
        v-if="Number(job.status) == t.n"
      >
        <job-opening-inst
          :item='job'
          @view="viewJob"
        />
      </v-flex>
    </v-layout>
  </template>

  <template v-if="!jobs.length">
    <v-layout justify-center align-center>
      <manage-no-data
        :loading="loading"
        :fetch="fetch"
        msg="You have not made any job openings :("
      >
        <div slot="icon" class="mb-3">
          <v-icon size="64px">work</v-icon>
        </div>
        <v-btn
          color="primary"
          slot="btn"
          @click="$bus.$emit('add--job-opening')"
        >Create</v-btn>
      </manage-no-data>
    </v-layout>
  </template>

  <dialog-job-opening/>
  <dialog-job-apply/>

</v-container>
</template>

<script>
import DialogJobApply from '@/include/dialogs/DialogJobApply'
import DialogJobOpening from '@/include/dialogs/DialogJobOpening'
import JobOpeningInst from '@/include/JobOpeningInst'
import ManageNoData from '@/include/ManageNoData'

export default {
  name: 'my-job-openings',
  components: {
    DialogJobApply,
    DialogJobOpening,
    JobOpeningInst,
    ManageNoData
  },
  data: () => ({
    url: '/jobs/my',
    jobs: [],
    loading: false,
    size: 'lg',
    types: [
      { title: 'Hidden', n: 0 },
      { title: 'Hiring', n: 1 },
      { title: 'On hold', n: 2 }
    ]
  }),

  watch: {
    loading(e) {
      this.$bus.progress.circular.MyJobOpenings.refresh = e
    }
  },

  created() {
    this.$bus.$on('add--job-opening', this.addJobOpening)
    this.$bus.$on('update--my-job-openings', this.fetch)
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('add--job-opening', this.addJobOpening)
    this.$bus.$off('update--my-job-openings', this.fetch)
  },

  methods: {
    addJobOpening() {
      this.$bus.$emit('dialog--job-opening.add')
    },
    viewJob(job, viewOnly) {
      this.$bus.$emit('dialog--job.apply', job, viewOnly)
    },

    countItems(n) {
      // check items with status n
      // return the length
      return this.jobs.filter(e => Number(e.status) == n).length
    },

    fetch() {
      this.loading = true
      this.$http.post(this.url).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }

        this.jobs = res.data.jobs
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
