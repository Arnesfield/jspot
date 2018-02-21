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
      <v-btn
        icon
        dark
        @click="$bus.nav.profile = !$bus.nav.profile"
        style="position: absolute; margin-left: -40px; margin-top: -40px;"
      >
        <v-icon>close</v-icon>
      </v-btn>
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

    </v-list>

    <template v-if="
      (user.places && user.places.length)
      || (user.job_tags && user.job_tags.length)
    ">
      <v-subheader v-text="'Interested in'"/>
      <v-list class="pt-0">

        <v-layout v-if="user.places && user.places.length">
          <v-flex xs2>
            <v-icon class="px-3 pt-2">location_on</v-icon>
          </v-flex>
          <v-flex xs8>
            <div class="pa-2">
              <template v-for="(place, i) in user.places">
                <span
                  :key="i"
                  class="subheading"
                >{{ place }}</span>{{ i != user.places.length-1 ? ', ' : '' }}
              </template>
            </div>
          </v-flex>
        </v-layout>
        
        <v-layout class="pb-3" v-if="user.job_tags && user.job_tags.length">
          <v-flex xs2>
            <v-icon class="px-3 pt-2">work</v-icon>
          </v-flex>
          <v-flex xs8>
            <div>
              <v-chip
                :key="i"
                v-for="(job, i) in user.job_tags"
              >{{ job }}</v-chip>
            </div>
          </v-flex>
        </v-layout>
        
        <v-divider/>

      </v-list>
    </template>

    <template v-if="user.socials && user.socials.length">
      <v-subheader v-text="'Social links'"/>
      <v-list dense class="pt-0">
        <v-list-tile
          :key="i"
          v-for="(link, i) in user.socials"
        >
          <v-list-tile-avatar class="thin-action">
            <v-avatar size="24" tile>
              <img :src="$wrap.urlImg(link)">
            </v-avatar>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <a :href="$wrap.url(link)" target="1">{{ link }}</a>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </template>

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

.thinner-action {
  width: 40px;
  min-width: 40px;
}
</style>
