<template>
  <v-toolbar
    flat
    dark
    :absolute="false"
    :fixed="false"
    class="primary lighten-1"
    :tabs="$route.name === 'Profile'"
  >
    <v-btn icon dark @click="iconClick">
      <v-icon>{{ $route.meta.icon }}</v-icon>
    </v-btn>
    <v-toolbar-title v-text="title"></v-toolbar-title>

    <template v-if="$route.name === 'Profile'">
      <v-tabs
        v-model="$bus.tabs.profile"
        slot="extension"
        color="primary lighten-1"
        align-with-title
      >
        <!-- tabs for employer -->
        <template v-if="$bus.profile.type == 3">
          <v-tab>Job offers</v-tab>
          <v-tab>Activity</v-tab>
        </template>
        <!-- tabs for employee -->
        <template v-if="$bus.profile.type == 4">
          <v-tab>Activity</v-tab>
        </template>
      </v-tabs>
    </template>

  </v-toolbar>
</template>

<script>
export default {
  name: 'toolbar-content',
  computed: {
    title() {
      return this.$route.meta.title || this.$route.name || 'Application'
    }
  },
  methods: {
    iconClick() {
      if (this.$route.name == 'Profile') {
        this.$bus.nav.profile = !this.$bus.nav.profile
      } else {
        this.$bus.navToggle()
      }
    }
  }
}
</script>
