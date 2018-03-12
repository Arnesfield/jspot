<template>
<div>
  <views-graph/>
  <div class="mt-4">
    <v-layout justify-center align-center>
      <v-flex lg10>
        <div>
          <profile-boost-form/>
        </div>
      </v-flex>
    </v-layout>
  </div>
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
  },
  data: () => ({

  }),
  methods: {

  }
}
</script>
