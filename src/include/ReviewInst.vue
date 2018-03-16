<template>
<v-card class="mb-2">
  <v-card-title class="pr-2 pt-2">
    <icon-img
      caption
      size="32"
      :item="item"
      fname="re_fname"
      img="re_img_src"
      color="accent"
      class="elevation-1 clickable"
      @click.native="$router.push('/profile/' + item.re_id)"
    />
    <div class="py-2">
    <div class="subheader">
      <div>
        <div class="subheading black--text">
          <router-link
            :to="'/profile/' + item.re_id"
            style="text-decoration: none"
          >{{ item.re_fname + ' ' + item.re_lname }}</router-link>
          <strong
            class="caption grey--text"
            style="font-weight: bold"
            v-if="$bus.session.user && $bus.session.user.id == item.re_id"
          >(me)</strong>
        </div>
        <div
          class="caption grey--text"
        >
          <template>{{ $wrap.datetime(item.created_at, true, true) }}</template>
          <div>
            <simple-star-rating :value="item.rating"/>
          </div>
        </div>
      </div>
    </div>
    </div>

    <template v-if="$bus.session.user && $bus.session.user.id == item.re_id">

      <v-spacer/>

      <v-menu left offset-y min-width="128">
        <v-btn
          icon
          slot="activator"
        >
          <v-icon color="grey">more_vert</v-icon>
        </v-btn>
        <v-list>
          <v-list-tile @click="$emit('delete')">
            <v-list-tile-action class="thin-36">
              <v-icon>delete</v-icon>
            </v-list-tile-action>
            <v-list-tile-title v-text="'Delete'"/>
          </v-list-tile>
        </v-list>
      </v-menu>

    </template>
    
  </v-card-title>
  <v-card-text class="pt-0">
    <div
      v-if="to"
      class="mb-2 grey--text"
    >to
      <router-link
        :to="'/profile/' + item.u_id"
        style="text-decoration: none"
      >{{ item.u_fname + ' ' + item.u_lname }}</router-link>
    </div>
    <template>{{ item.body }}</template>
  </v-card-text>
</v-card>
</template>

<script>
import IconImg from '@/include/IconImg'
import SimpleStarRating from '@/include/SimpleStarRating'

export default {
  name: 'review-inst',
  components: {
    IconImg,
    SimpleStarRating
  },
  props: {
    item: Object,
    to: {
      type: Boolean,
      default: false
    }
  }
}
</script>
