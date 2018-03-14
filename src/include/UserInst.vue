<template>
<v-card
  ripple
  v-if="item"
  class="clickable"
  @click.native="$router.push('/profile/' + item.id)"
>
  <v-card-title class="pb-0" style="padding: 8px !important">
    <v-layout class="">
      <div class="pa-2 mr-3 grey lighten-3">
        <icon-img
          :item="item"
          img="img_src"
          color="accent"
          class="elevation-1"
          textClass="white--text headline"
        />
      </div>

      <div class="py-3">
        <div
          class="subheading"
        >{{ $wrap.fullname(item) }}</div>
        <div
          class="caption grey--text"
        >{{ $wrap.userType(item.type) }}</div>

        <v-layout
          class="pa-2"
          v-if="tempLoc = totalVisiblePlaces(item)"
        >
          <div>
            <v-tooltip top>
              <v-icon
                slot="activator"
                size="21.6px"
                class="pa-0"
              >location_on</v-icon>
              <span>Location</span>
            </v-tooltip>
          </div>
          <div class="px-2">
            <template v-for="i in tempLoc">
              <span
                :key="i-1"
                class="body-1"
              >{{ item.places[i-1] }}</span>{{ i-1 != tempLoc-1 ? ',' : '' }}
            </template>
          </div>
        </v-layout>

        <div>
          <v-chip :key="i-1" v-for="i in totalVisibleTags(item)">
            {{ item.job_tags[i-1] }}
          </v-chip>
        </div>
      </div>

    </v-layout>
  </v-card-title>
</v-card>
</template>

<script>
import IconImg from '@/include/IconImg'

export default {
  name: 'user-inst',
  components: {
    IconImg
  },
  props: {
    item: Object
  },
  data: () => ({
    totalVisible: 5
  }),
  methods: {
    totalVisiblePlaces(item) {
      return item.places.length > this.totalVisible
        ? this.totalVisible : item.places.length
    },

    totalVisibleTags(item) {
      return item.job_tags.length > this.totalVisible
        ? this.totalVisible : item.job_tags.length
    }
  }
}
</script>
