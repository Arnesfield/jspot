<template>
<v-navigation-drawer
  app
  fixed
  :mini-variant="$bus.nav.miniVariant"
  :clipped="true"
  v-model="$bus.nav.model"
>
  <nav-user/>
  <v-list
    class="pt-2"
    :key="i"
    v-for="(list, i) in lists"
    :subheader="Boolean(list.header)"
  >
    <v-subheader
      class="grey--text"
      v-if="list.header"
    >{{ list.header }}</v-subheader>
    <v-tooltip
      :key="i"
      v-for="(item, i) in list.items"
      :disabled="!$bus.nav.miniVariant"
      right
    >
      <v-list-tile
        ripple
        slot="activator"
        :to="item.to"
        :exact-active-class="item.to"
      >
        <v-list-tile-action>
          <v-icon v-html="item.icon"></v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title v-text="item.title"></v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <span>{{ item.title }}</span>
    </v-tooltip>
  </v-list>
  <v-footer fixed :height="null" color="white">
    <v-list style="width: 100%">
      <v-tooltip right :disabled="!$bus.nav.miniVariant">
        <v-list-tile
          ripple
          slot="activator"
          @click="$bus.nav.miniVariant = !$bus.nav.miniVariant">
          <v-list-tile-action>
            <v-btn
              icon
              :ripple="false"
              @click.stop="$bus.nav.miniVariant = !$bus.nav.miniVariant">
              <v-icon v-html="$bus.nav.miniVariant ? 'chevron_right' : 'chevron_left'"></v-icon>
            </v-btn>
          </v-list-tile-action>
          <v-list-tile-content>{{ collapseText }}</v-list-tile-content>
        </v-list-tile>
        <span>{{ collapseText }}</span>
      </v-tooltip>
    </v-list>
  </v-footer>
</v-navigation-drawer>
</template>

<script>
import NavUser from './nav/NavUser'

export default {
  name: 'navigation',
  components: {
    NavUser
  },
  data: () => ({
    lists: [
      {
        header: '',
        items: [
          { title: 'Home', icon: 'home', to: '/' }
        ]
      }
    ]
  }),
  computed: {
    collapseText() {
      return this.$bus.nav.miniVariant ? 'Expand' : 'Collapse'
    }
  }
}
</script>
