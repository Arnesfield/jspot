<template>
  <v-app>
    <navigation/>
    <toolbar/>
    <v-content>
      <toolbar-content/>
      <router-view/>
    </v-content>
  </v-app>
</template>

<script>
import Navigation from '@/include/Navigation'
import Toolbar from '@/include/Toolbar'
import ToolbarContent from '@/include/ToolbarContent'

export default {
  name: 'App',
  components: {
    Navigation,
    Toolbar,
    ToolbarContent
  }
}
</script>
