<template>
  <div class="bg" style="background-image: url('./static/images/bg-2.sm.jpg')">
    <v-container fluid class="no-bg dim pa-3">
      <icon-img
        :item="this.$bus.session.user"
        img="img_src"
        color="accent"
        class="elevation-6"
        textClass="white--text headline"
      />
    </v-container>
    <v-list class="no-bg dim">
      <v-list-tile>
        <v-list-tile-content>
          <v-list-tile-title class="title white--text">{{ $wrap.fullname($bus.session.user) }}</v-list-tile-title>
          <v-list-tile-sub-title class="grey--text text--lighten-3">{{ email }}</v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </div>
</template>

<script>
import IconImg from '@/include/IconImg'

export default {
  name: 'nav-user',
  components: {
    IconImg
  },
  computed: {
    email() {
      let user = this.$bus.session.user
      if (typeof user !== 'object' || user === null) {
        return ''
      }
      return user.email
    }
  }
}
</script>
