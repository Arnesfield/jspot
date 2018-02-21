<template>
  <v-toolbar
    app
    dark
    :clipped-left="$bus.nav.clipped"
    color="primary"
  >
    <v-toolbar-side-icon @click.stop="$bus.navToggle"/>
    <v-toolbar-title
      @click="$router.push('/')"
      class="clickable"
    >JSpot</v-toolbar-title>

    <!-- side -->
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
      <v-spacer/>
      <v-tooltip left v-if="$bus.profile.type == 3">
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
      <btn-refresh
        click="update--my-job-openings"
        :refresh="$bus.progress.circular.JobOpenings.refresh"
      />
    </template>

  </v-toolbar>
</template>

<script>
import BtnRefresh from '@/include/BtnRefresh'

export default {
  name: 'toolbar',
  components: {
    BtnRefresh
  }
}
</script>
