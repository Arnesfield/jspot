<template>
<!-- :fill-height="!jobs.length" -->
<v-container
  :style="{ 'height': 'calc(100% - 64px)' }"
  style="display: flex"
  v-bind="{ ['grid-list-' + size]: true }"
>
  <template v-for="(t, i) in types" v-if="countItems(t.n)">
    <v-subheader :key="'subheader-' + i">{{ t.title }}</v-subheader>
    <v-layout :key="'layout-' + i" row wrap>
      <v-flex
        md4
        lg3
        xs12
        sm6
        :key="i"
        v-for="(job, i) in jobs"
        v-if="Number(job.status) == t.n"
      >
        <job-opening-inst :item='job'/>
      </v-flex>
    </v-layout>
  </template>

  <template v-if="!jobs.length">
    <v-layout justify-center align-center>
      <manage-no-data
        :loading="loading"
        :fetch="fetch"
        msg="You have not made any job openings :("
      />
    </v-layout>
  </template>

  <dialog-job-opening/>

</v-container>
</template>

<script>
import DialogJobOpening from '@/include/dialogs/DialogJobOpening'
import JobOpeningInst from '@/include/JobOpeningInst'
import ManageNoData from '@/include/ManageNoData'

export default {
  name: 'job-openings',
  components: {
    DialogJobOpening,
    JobOpeningInst,
    ManageNoData
  },
  data: () => ({
    url: '/jobs/my_openings',
    jobs: [],
    loading: false,
    size: 'lg',
    types: [
      { title: 'Active', n: 1 },
      { title: 'Hidden', n: 0 }
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

  methods: {
    addJobOpening() {
      this.$bus.$emit('dialog--job-opening.add')
    },

    countItems(n) {
      // check items with status n
      // return the length
      return this.jobs.filter(e => Number(e.status) == n).length
    },

    fetch() {
      this.loading = true
      this.$http.post(this.url).then(res => {
        console.error(res.data)
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
