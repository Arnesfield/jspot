<template>
  <v-app>
    <navigation/>
    <toolbar/>
    <v-content>
      <router-view/>
    </v-content>
  </v-app>
</template>

<script>
import Navigation from '@/include/Navigation'
import Toolbar from '@/include/Toolbar'

export default {
  name: 'App',
  components: {
    Navigation,
    Toolbar
  }
}
</script>
