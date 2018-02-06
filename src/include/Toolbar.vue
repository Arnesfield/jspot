<template>
<v-toolbar app :clipped-left="true">
  <v-toolbar-side-icon @click.stop="$bus.nav.model = !$bus.nav.model"></v-toolbar-side-icon>
  <v-toolbar-title v-text="title"></v-toolbar-title>
  <v-spacer></v-spacer>
</v-toolbar>
</template>

<script>
export default {
  computed: {
    title() {
      return this.$route.meta.title || 'Application'
    }
  }
}
</script>
