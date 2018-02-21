<template>
  <v-toolbar
    flat
    dark
    :absolute="false"
    :fixed="false"
    class="primary lighten-1"
  >
    <v-btn icon dark @click="iconClick">
      <v-icon>{{ $route.meta.icon }}</v-icon>
    </v-btn>
    <v-toolbar-title v-text="title"></v-toolbar-title>

    <template v-if="$route.name === 'ManageUsers'">
      <v-spacer/>
      <btn-refresh
        click="update--manage-users"
        :refresh="$bus.progress.circular.ManageUsers.refresh"
      />
    </template>
    <template v-if="$route.name === 'MyJobOpenings'">
      <v-spacer/>
      <btn-refresh
        click="update--my-job-openings"
        :refresh="$bus.progress.circular.MyJobOpenings.refresh"
      />
    </template>

  </v-toolbar>
</template>

<script>
import BtnRefresh from '@/include/BtnRefresh'

export default {
  name: 'toolbar-content',
  components: {
    BtnRefresh
  },
  computed: {
    title() {
      return this.$route.meta.title || this.$route.name || 'Application'
    }
  },
  methods: {
    iconClick() {
      if (this.$route.name == 'Profile') {
        this.$bus.nav.profile = !this.$bus.nav.profile
      }
    }
  }
}
</script>
