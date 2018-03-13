<template>
<div>
  <v-layout
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
import ProfileBoostForm from '@/include/forms/ProfileBoostForm'

export default {
  name: 'profile-boost',
  components: {
    ProfileBoostForm
  }
}
</script>
