<template>
<v-container
  :style="!jobs.length ? {
    'height': '100%',
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
        :md6="!listView"
        :lg4="!listView"
        :key="i"
        v-for="(job, i) in jobs"
        v-if="Number(job.status) == t.n"
      >
        <job-opening-inst
          slim
          :item='job'
          @apply="apply"
          @view="viewJob"
          :appliedIds="appliedIds"
        />
      </v-flex>
    </v-layout>
  </template>

  <template v-if="!jobs.length">
    <v-layout justify-center align-center>
      <manage-no-data
        :loading="loading"
        :fetch="fetch"
        msg="No job openings :("
      >
        <div slot="icon" class="mb-3">
          <v-icon size="64px">work</v-icon>
        </div>
        <v-btn
          v-if="$bus.session.user && user && $bus.session.user.id == user.id"
          color="primary"
          slot="btn"
          @click="$bus.$emit('add--job-opening')"
        >Create</v-btn>
      </manage-no-data>
    </v-layout>
  </template>

  <dialog-job-opening v-if="$bus.session.user && user && $bus.session.user.id == user.id"/>
  <dialog-job-apply @success="fetch"/>

</v-container>
</template>

<script>
import DialogJobApply from '@/include/dialogs/DialogJobApply'
import DialogJobOpening from '@/include/dialogs/DialogJobOpening'
import JobOpeningInst from '@/include/JobOpeningInst'
import ManageNoData from '@/include/ManageNoData'
import qs from 'qs'

export default {
  name: 'job-openings',
  props: {
    user: {
      type: Object,
      default: null
    },
    value: Boolean
  },
  components: {
    DialogJobApply,
    DialogJobOpening,
    JobOpeningInst,
    ManageNoData
  },
  data: () => ({
    url: '/jobs',
    jobs: [],
    appliedIds: [],
    loading: false,
    size: 'lg',
    types: [
      { title: 'Hidden', n: 0 },
      { title: 'Hiring', n: 1 },
      { title: 'On hold', n: 2 }
    ],
    listView: false
  }),

  watch: {
    user(e) {
      this.fetch()
    },
    jobs(e) {
      this.$emit('input', e.length == 0)
    },
    loading(e) {
      this.$bus.progress.circular.JobOpenings.refresh = e
    }
  },

  created() {
    this.listView = this.$bus.profile.listView
    this.$bus.$on('watch--profile.listView', this.profileListView)
    this.$bus.$on('add--job-opening', this.addJobOpening)
    this.$bus.$on('update--my-job-openings', this.fetch)
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('watch--profile.listView', this.profileListView)
    this.$bus.$off('add--job-opening', this.addJobOpening)
    this.$bus.$off('update--my-job-openings', this.fetch)
  },

  methods: {
    profileListView(to, from) {
      this.listView = to
    },

    apply(job) {
      this.$bus.$emit('dialog--job.apply', job)
    },
    viewJob(job, viewOnly) {
      this.$bus.$emit('dialog--job.apply', job, viewOnly)
    },

    addJobOpening() {
      this.$bus.$emit('dialog--job-opening.add')
    },

    countItems(n) {
      // check items with status n
      // return the length
      return this.jobs.filter(e => Number(e.status) == n).length
    },

    fetch() {
      let id = 0
      if (this.user !== null) {
        id = this.user.id
      }

      this.loading = true
      this.$http.post(this.url, !this.user ? undefined : qs.stringify({
        id: id
      })).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.jobs = res.data.jobs
        this.appliedIds = res.data.appliedIds
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
