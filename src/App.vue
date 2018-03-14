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
    <navigation v-if="!exception && authCheck"/>
    <v-content>
      <toolbar-content v-if="!exception && authCheck"/>
      <router-view/>
      <fab/>
      <dialog-delete/>
      <dialog-confirm/>
      <dialog-login/>
    </v-content>
    <toolbar v-if="!exception && authCheck"/>
    <snackbar/>
  </v-app>
</template>

<script>
import Navigation from '@/include/Navigation'
import Fab from '@/include/Fab'
import Toolbar from '@/include/Toolbar'
import ToolbarContent from '@/include/ToolbarContent'
import DialogDelete from '@/include/dialogs/DialogDelete'
import DialogConfirm from '@/include/dialogs/DialogConfirm'
import DialogLogin from '@/include/dialogs/DialogLogin'
import Snackbar from '@/include/Snackbar'

export default {
  name: 'App',
  components: {
    Navigation,
    Fab,
    Toolbar,
    ToolbarContent,
    DialogDelete,
    DialogConfirm,
    DialogLogin,
    Snackbar
  },
  computed: {
    authCheck() {
      return this.$bus.authHas(this.$route.meta.auth, this.$bus.session.auth, 10)
        && !this.$bus.authHas(this.$route.meta.auth, 0)
    },
    exception() {
      return this.$route.name == 'NotFound'
    }
  },

  created() {
    this.$bus.$on('get-route', (emit, to, from) => {
      this.$bus.$emit(emit, this.$route, to, from)
    })
  }
}
</script>
