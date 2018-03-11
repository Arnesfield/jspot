<template>
  <v-toolbar
    app
    dark
    :clipped-left="$bus.nav.clipped"
    color="primary"
  >
    <v-toolbar-side-icon @click.stop="$bus.navToggle"/>
    <v-flex hidden-xs-only hidden-sm-only xs2>
      <v-toolbar-title
        @click="$router.push('/')"
        class="clickable"
      >JSpot</v-toolbar-title>
    </v-flex>

    <!-- search -->
    <!-- <template v-if="$route.name === 'Dashboard'"> -->
      <v-flex xs12 sm9 md7>
        <searchbar/>
      </v-flex>
    <!-- </template> -->

    <!-- side -->
    <template v-if="$route.name === 'Dashboard'">
      <v-spacer/>
      <btn-refresh
        click="update--dashboard"
        :refresh="$bus.progress.circular.Dashboard.refresh"
      />
    </template>
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
import Searchbar from '@/include/Searchbar'
import BtnRefresh from '@/include/BtnRefresh'

export default {
  name: 'toolbar',
  components: {
    Searchbar,
    BtnRefresh
  }
}
</script>
