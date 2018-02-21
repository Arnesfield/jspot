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
        :md4="!listView"
        xs12
        :sm6="!listView"
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
        msg="No job openings :("
      >
        <div slot="icon" class="mb-3">
          <v-icon size="64px">work</v-icon>
        </div>
      </manage-no-data>
    </v-layout>
  </template>

</v-container>
</template>

<script>
import JobOpeningInst from '@/include/JobOpeningInst'
import ManageNoData from '@/include/ManageNoData'
import qs from 'qs'

export default {
  name: 'job-openings',
  props: {
    user: Object,
    value: Boolean
  },
  components: {
    JobOpeningInst,
    ManageNoData
  },
  data: () => ({
    url: '/jobs',
    jobs: [],
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
    }
  },

  created() {
    this.listView = this.$bus.profile.listView
    this.$bus.$on('watch--profile.listView', (to, from) => {
      this.listView = to
    })
    this.fetch()
  },

  methods: {
    countItems(n) {
      // check items with status n
      // return the length
      return this.jobs.filter(e => Number(e.status) == n).length
    },

    fetch() {
      if (!this.user) {
        this.jobs = []
        return
      }

      let id = this.user.id
      this.loading = true
      this.$http.post(this.url, qs.stringify({
        id: id
      })).then(res => {
        console.error(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.jobs = res.data.jobs
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
