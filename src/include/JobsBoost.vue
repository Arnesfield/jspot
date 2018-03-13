<template>
<div>

  <v-container
    class="pa-0"
    v-if="jobs.length"
    grid-list-lg
  >
    <v-subheader>My job openings</v-subheader>
    <v-layout
      row
      wrap
    >
      <v-flex
        xs12
        sm12
        md6
        lg4
        :key="i"
        v-for="i in maxCol"
        class="pt-0"
      >
        <template v-for="(item) in indexCheck(i)">
          <boost-job-opening-inst
            :item="item"
            :key="'job-' + item.id"
            :color="colors[item.id]"
            class="mb-2 pa-0"
            @view="viewJob"
            @boost="boost"
          />
        </template>
      </v-flex>
    </v-layout>
  </v-container>

  <manage-no-data
    v-else
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
      @click="$router.push('/my/jobs')"
    >Create</v-btn>
  </manage-no-data>

  <dialog-job-apply/>
  
</div>
</template>

<script>
import DialogJobApply from '@/include/dialogs/DialogJobApply'
import BoostJobOpeningInst from '@/include/BoostJobOpeningInst'
import ManageNoData from '@/include/ManageNoData'

export default {
  name: 'jobs-boost',
  components: {
    DialogJobApply,
    BoostJobOpeningInst,
    ManageNoData
  },
  props: {
    colors: Object
  },
  data: () => ({
    url: '/jobs/boosts',
    jobs: [],
    loading: false,
    maxCol: 3
  }),

  created() {
    this.$bus.$on('update--boosts', this.fetch)
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('update--boosts', this.fetch)
  },

  methods: {
    boost(cb) {
      this.fetch(cb)
    },

    indexCheck(n) {
      return this.jobs.filter((e, i) => {
        while (i >= this.maxCol) {
          i -= this.maxCol
        }
        return n-1 === i
      })
    },
    fetch(successCb, errorCb) {
      this.loading = true
      this.$http.post(this.url).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.jobs = res.data.jobs
        this.loading = false
        if (typeof successCb === 'function') {
          successCb()
        }
      }).catch(e => {
        console.error(e)
        this.loading = false
        if (typeof errorCb === 'function') {
          errorCb()
        }
      })
    },

    viewJob(job, viewOnly) {
      this.$bus.$emit('dialog--job.apply', job, viewOnly)
    }
  }
}
</script>
