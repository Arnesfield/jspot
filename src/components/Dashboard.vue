<template>
  <search-dashboard>
    <v-flex xs12 sm4 slot="col">
      <boosted-list/>
    </v-flex>
  </search-dashboard>
</template>

<script>
import BoostedList from '@/include/BoostedList'
import SearchDashboard from '@/include/SearchDashboard'

export default {
  name: 'dashboard',
  components: {
    BoostedList,
    SearchDashboard
  }
}
</script>
