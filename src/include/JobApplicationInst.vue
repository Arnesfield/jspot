<template>
<v-list-tile
  ripple
  @click="$emit('view', item)"
>
  <v-list-tile-action>
    <apply-status :item="item"/>
  </v-list-tile-action>
  <v-list-tile-content>
    <v-list-tile-title v-text="item.a_subject + ' (' + item.title + ')'"/>
    <v-list-tile-sub-title v-text="$wrap.datetime(item.a_created_at, true, true)"/>
    <v-list-tile-sub-title v-text="item.a_body"/>
  </v-list-tile-content>
</v-list-tile>
</template>

<script>
import ApplyStatus from '@/include/ApplyStatus'

export default {
  name: 'job-application-inst',
  components: {
    ApplyStatus
  },
  props: {
    item: Object
  }
}
</script>
