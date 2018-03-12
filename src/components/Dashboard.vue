<template>
<div>
  <v-container
    grid-list-lg
    class="pb-0"
    v-if="$bus.authHas($bus.session.auth, [3, 4])"
  >
    <v-layout row wrap>
      <v-flex xs12 sm12 md12 lg4>
        <boost-ad/>
      </v-flex>
      <v-flex hidden-sm-and-down md12 lg8>
        <views-graph/>
      </v-flex>
    </v-layout>
  </v-container>

  <search-dashboard ref="searchDashboard">
    <boosted-list
      slot="col"
      @fetched="fetchedBoosted"
    />
  </search-dashboard>
</div>
</template>

<script>
import ViewsGraph from '@/include/ViewsGraph'
import BoostAd from '@/include/BoostAd'
import BoostedList from '@/include/BoostedList'
import SearchDashboard from '@/include/SearchDashboard'

export default {
  name: 'dashboard',
  components: {
    ViewsGraph,
    BoostAd,
    BoostedList,
    SearchDashboard
  },
  methods: {
    fetchedBoosted(e) {
      if (this.$refs.searchDashboard) {
        this.$refs.searchDashboard.fetchedBoosted(e)
      }
    }
  }
}
</script>
