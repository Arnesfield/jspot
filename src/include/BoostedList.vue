<template>
<v-flex
  xs12
  sm12
  md6
  lg4
  v-if="combined.length"
>
  <v-subheader>Boosted</v-subheader>

  <div class="elevation-1 white">
    <template v-for="(item) in combined">
      <job-opening-inst
        v-if="item.title"
        slim
        :item="item"
        :key="'job-' + item.id"
        class="mb-2 pa-0"
        @view="viewJob"
        @apply="apply"
      />
      <user-inst
        :key="'user-' + item.id"
        v-else-if="item.fname"
        class="mb-2 pa-0"
        :item="item"
      />
    </template>
  </div>
</v-flex>
</template>

<script>
import createCombined from '@/assets/js/createCombined'
import DialogJobApply from '@/include/dialogs/DialogJobApply'
import JobOpeningInst from '@/include/JobOpeningInst'
import UserInst from '@/include/UserInst'

export default {
  name: 'boosted-list',
  components: {
    DialogJobApply,
    JobOpeningInst,
    UserInst
  },
  data: () => ({
    url: '/boost/suggest',
    jobs: [],
    users: [],
    combined: [],
    loading: false
  }),
  created() {
    this.$bus.$on('update--dashboard', this.fetch)
    this.$bus.$on('change--session.auth', this.fetchWrap)
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('update--dashboard', this.fetch)
    this.$bus.$off('change--session.auth', this.fetchWrap)
  },

  methods: {
    fetchWrap() {
      this.fetch()
    },
    fetch() {
      this.loading = true
      this.$http.post(this.url).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Exception('Request failure.')
        }
        this.jobs = res.data.jobs
        this.users = res.data.users
        this.combined = createCombined(this.jobs, this.users)
        this.$emit('fetched', this.combined)
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
