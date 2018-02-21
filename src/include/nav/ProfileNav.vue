<template>
<v-navigation-drawer
  :app="$route.name == 'Profile'"
  fixed
  :clipped="true"
  v-model="$bus.nav.profile"
  width="348"
  style="box-shadow: 0 !important"
>
<template v-if="user">
  <div class="bg" style="background-image: url('./static/images/bg-2.sm.jpg')">
    <v-container fluid class="no-bg dim pa-5">

      <v-avatar class="mt-2 ml-4 primary lighten-1 elevation-4" size="204" style="position: absolute">
        <template v-if="imgSrc">
          <img v-if="imgSrc.isImg" :src="imgSrc.text" alt="avatar">
          <span
            v-else
            class="white--text headline"
            style="font-size: 48px !important"
          >{{ imgSrc.text }}</span>
        </template>
        <template v-else>
          <span class="white--text headline">?</span>
        </template>
      </v-avatar>

    </v-container>
    <v-list class="no-bg dim">
      <v-list-tile/>
    </v-list>
  </div>
  <div class="py-3" style="margin-top: 114px">
    <div class="px-4 mb-3">
      <div class="display-1 mb-3">{{ $wrap.fullname(user) }}</div>
      <div class="grey--text">{{ user.bio }}</div>
    </div>
    <v-list>
      <v-divider/>

      <v-list-tile>
        <v-list-tile-action class="thin-action">
          <v-icon v-if="user.type == 3">person</v-icon>
          <v-icon v-else-if="user.type == 4">person_outline</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title v-text="$wrap.userType(user.type)"/>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile>
        <v-list-tile-action class="thin-action">
          <v-icon>email</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title v-text="user.email"/>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile>
        <v-list-tile-action class="thin-action">
          <v-icon>phone</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title v-text="user.contact"/>
        </v-list-tile-content>
      </v-list-tile>

      <v-divider/>

      <v-list-tile
        ripple
        tabindex="1"
        @click="$bus.nav.profile = !$bus.nav.profile">
        <v-list-tile-action class="thin-action">
          <v-icon>chevron_left</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>Close</v-list-tile-content>
      </v-list-tile>

    </v-list>
  </div>
</template>
</v-navigation-drawer>
</template>

<script>
export default {
  name: 'profile-nav',
  props: {
    user: Object,
    editable: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    
  }),
  computed: {
    imgSrc() {
      let user = this.user
      if (typeof user !== 'object' || user === null) {
        return null
      }
      if (typeof user.img_src !== 'string' || !user.img_src.length) {
        return {
          isImg: false,
          text: user.fname.charAt(0).toUpperCase()
        }
      } 
      return {
        isImg: true,
        text: user.img_src
      }
    }
  },

  watch: {
    user(to, from) {
      if (!to) {
        this.$bus.nav.profile = false
      } else {
        this.$bus.nav.profile = true
      }
    }
  }
}
</script>

<style scoped>
.thin-action {
  width: 48px;
  min-width: 48px;
}
</style>
