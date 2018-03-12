<template>
<v-card>
  <v-card-title class="title" primary-title>
    <template v-if="isBoosted">You are boosted!</template>
    <template v-else>Boost</template>
  </v-card-title>
  <v-card-text class="pt-0" :class="{ 'pb-2': !isBoosted }">
    <div class="grey--text text--darken-1">
      <template v-if="isBoosted">
        <div class="subheading">
          <template>You have boosted! You can boost again after</template>
          <strong>{{ endsAt }}</strong>.
        </div>
      </template>
      <template v-else>
        <div>Let others find you by being on promoted on top of the dashboard!</div>
      </template>
    </div>
  </v-card-text>
  <v-card-actions
    class="pt-0"
    v-if="!isBoosted"
  >
    <v-btn
      flat
      to="/boosts"
      color="primary"
      :disabled="loading || !allow"
      :loading="loading"
    >Learn more</v-btn>
  </v-card-actions>
</v-card>
</template>

<script>
export default {
  name: 'boost-ad',
  data: () => ({
    url: '/boost/profileCheck',
    allow: false,
    endsAt: null,
    loading: false
  }),
  computed: {
    isBoosted() {
      return !this.allow && this.endsAt
    }
  },
  created() {
    this.$bus.$on('update--dashboard', this.checkBoosted)
    this.checkBoosted()
  },
  beforeDestroy() {
    this.$bus.$off('update--dashboard', this.checkBoosted)
  },
  methods: {
    checkBoosted() {
      this.loading = true
      this.$http.post(this.url).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.allow = res.data.allow
        this.endsAt = res.data.endsAt ? res.data.endsAt : null
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
