<template>
<v-container>
  <profile-boost class="mt-2"/>
  <views-graph
    class="mt-4"
    @color="setColors"
  />
  <jobs-boost
    class="mt-2"
    :colors="colors"
    v-if="$bus.authHas($bus.session.auth, 3)"
  />
</v-container>
</template>

<script>
import JobsBoost from '@/include/JobsBoost'
import ViewsGraph from '@/include/ViewsGraph'
import ProfileBoost from '@/include/ProfileBoost'

export default {
  name: 'boosts',
  components: {
    JobsBoost,
    ViewsGraph,
    ProfileBoost
  },
  data: () => ({
    colors: {}
  }),
  methods: {
    setColors(colors) {
      this.colors = colors
    }
  }
}
</script>
