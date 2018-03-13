<template>
<v-dialog
  v-model="show"
  transition="fade-transition"
  scrollable
  :persistent="loading"
  max-width="480"
>
  <login-form
    v-if="show"
    style="height: 100%"
    class="elevation-0"
    dialog
    :dialogCloseFn="() => { show = false }"
  />
</v-dialog>
</template>

<script>
import LoginForm from '@/include/forms/LoginForm'

export default {
  name: 'dialog-login',
  components: {
    LoginForm
  },
  data: () => ({
    show: false,
    loading: false
  }),
  created() {
    this.$bus.$on('dialog--login', this.login)
  },
  beforeDestroy() {
    this.$bus.$off('dialog--login', this.login)
  },
  methods: {
    login() {
      this.show = true
    }
  }
}
</script>
