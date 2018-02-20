<template>
<v-container class="full-height" fluid>
  <v-layout class="center-y full-height">
    <v-flex hidden-xs-only sm2 md3 lg4/>
    <v-flex sm8 md6 lg4>
      <login-form/>
      <v-layout class="pt-3">
        <v-flex xs-6>
          <div class="caption grey--text">&copy; jspot {{ (new Date()).getFullYear() }}</div>
        </v-flex>
        <v-flex xs-6>
          <div class="caption grey--text text-xs-right">bukang liwayway</div>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</v-container>
</template>

<script>
import LoginForm from '@/include/forms/LoginForm'

export default {
  name: 'login',
  components: {
    LoginForm
  }
}
</script>
