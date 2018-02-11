<template>
  <v-toolbar
    app
    dark
    :clipped-left="$bus.nav.clipped"
    color="primary"
  >
    <v-toolbar-side-icon @click.stop="$bus.navToggle"/>
    <v-toolbar-title>JSpot</v-toolbar-title>
  </v-toolbar>
</template>

<script>
export default {
  name: 'toolbar'
}
</script>
