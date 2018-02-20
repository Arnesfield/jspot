<template>
<v-card>
  <v-card-title primary-title class="pb-0">
    <div>
      <div class="headline">{{ item.title }}</div>
      <div class="subheading">{{ $wrap.date(item.dateFrom) + ' to ' + $wrap.date(item.dateTo) }}</div>
      <div class="subheading">{{ item.timeFrom + ' to ' + item.timeTo }}</div>
      <div class="grey--text">{{ item.description }}</div>
    </div>
  </v-card-title>

  <v-card-actions>
    <div>
      <v-tooltip top>
        <v-btn icon slot="activator">
          <v-icon color="grey">info</v-icon>
        </v-btn>
        <span>View detailed</span>
      </v-tooltip>
      <v-tooltip top v-if="$bus.session.user.id == item.created_by">
        <v-btn icon slot="activator">
          <v-icon color="grey">edit</v-icon>
        </v-btn>
        <span>Edit</span>
      </v-tooltip>
    </div>
    <v-spacer/>
    <status :item="item"/>
    <v-tooltip top v-if="this.$bus.session.user.id != item.created_by">
      <v-btn icon slot="activator">
        <v-icon color="primary">arrow_forward</v-icon>
      </v-btn>
      <span>Apply</span>
    </v-tooltip>
  </v-card-actions>
</v-card>
</template>

<script>
import Status from '@/include/Status'

export default {
  name: 'job-opening-inst',
  components: {
    Status
  },
  props: {
    item: {
      type: Object,
      default: null
    }
  }
}
</script>
