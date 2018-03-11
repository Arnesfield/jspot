<template>
<div>
  <v-subheader>Boosted</v-subheader>

  <div class="elevation-1 white">

  </div>
</div>
</template>

<script>
import createCombined from '@/assets/js/createCombined'

export default {
  name: 'boosted-list',
  data: () => ({
    url: '/boost/suggest',
    jobs: [],
    users: [],
    combined: [],
    loading: false
  }),
  created() {
    this.$bus.$on('update--dashboard', this.fetch)
    this.$bus.$on('change--session.auth', () => { this.fetch() })
    this.fetch()
  },
  methods: {
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
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
