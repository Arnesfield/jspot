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

    <template v-if="$route.name === 'Profile'">
      <template v-if="$bus.profile.type == 3">
        <v-spacer/>
        <v-tooltip left>
          <v-btn
            icon
            slot="activator"
            @click="$bus.profile.listView = !$bus.profile.listView"
          >
            <v-icon v-if="!$bus.profile.listView">view_list</v-icon>
            <v-icon v-else>view_module</v-icon>
          </v-btn>
          <span v-if="!$bus.profile.listView">View as list</span>
          <span v-else>View as grid</span>
        </v-tooltip>
      </template>

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
