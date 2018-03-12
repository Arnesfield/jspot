<template>
<v-container>
  <profile-boost/>
  <jobs-boost v-if="$bus.authHas($bus.session.auth, 3)"/>
</v-container>
</template>

<script>
import JobsBoost from '@/include/JobsBoost'
import ProfileBoost from '@/include/ProfileBoost'

export default {
  name: 'boosts',
  components: {
    JobsBoost,
    ProfileBoost
  }
}
</script>
