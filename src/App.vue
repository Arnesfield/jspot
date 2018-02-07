<template>
  <v-app>
    <navigation v-if="$bus.componentWithAuth"/>
    <toolbar v-if="$bus.componentWithAuth"/>
    <v-content>
      <toolbar-content v-if="$bus.componentWithAuth"/>
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
  },

  created() {
    this.$bus.$on('get-route', (emit, to, from) => {
      this.$bus.$emit(emit, this.$route, to, from)
    })
  }
}
</script>
