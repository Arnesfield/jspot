<template>
  <v-app :dark="$bus.settings.dark">
    <v-progress-linear
      color="warning"
      height="4"
      :active="$bus.progress.active"
      indeterminate
      style="z-index: 5; position: fixed"
      class="ma-0"
    />
    <navigation v-if="authCheck"/>
    <toolbar v-if="authCheck"/>
    <v-content>
      <toolbar-content v-if="authCheck"/>
      <router-view/>
      <fab/>
      <dialog-delete/>
    </v-content>
    <snackbar/>
  </v-app>
</template>

<script>
import Navigation from '@/include/Navigation'
import Fab from '@/include/Fab'
import Toolbar from '@/include/Toolbar'
import ToolbarContent from '@/include/ToolbarContent'
import DialogDelete from '@/include/dialogs/DialogDelete'
import Snackbar from '@/include/Snackbar'

export default {
  name: 'App',
  components: {
    Navigation,
    Fab,
    Toolbar,
    ToolbarContent,
    DialogDelete,
    Snackbar
  },
  computed: {
    authCheck() {
      return this.$bus.authCheck(this.$route.meta.auth)
    }
  },

  created() {
    this.$bus.$on('get-route', (emit, to, from) => {
      this.$bus.$emit(emit, this.$route, to, from)
    })
  }
}
</script>
