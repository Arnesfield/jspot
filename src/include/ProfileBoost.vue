<template>
<div>
  <views-graph/>
  <v-layout
    class="mt-4"
    align-center
    justify-center
  >
    <v-flex lg10>
      <div>
        <profile-boost-form/>
      </div>
    </v-flex>
  </v-layout>
</div>
</template>

<script>
import ViewsGraph from '@/include/ViewsGraph'
import ProfileBoostForm from '@/include/forms/ProfileBoostForm'

export default {
  name: 'profile-boost',
  components: {
    ViewsGraph,
    ProfileBoostForm
  }
}
</script>
