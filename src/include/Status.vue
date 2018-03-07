<template>
<v-tooltip top>
  <v-btn icon slot="activator">
    <v-icon color="error" v-if="status(0)">cancel</v-icon>
    <v-icon color="success" v-else-if="status(1)">check_circle</v-icon>
    <v-icon color="warning" v-else-if="status(2)">pan_tool</v-icon>
  </v-btn>
  <span
    :key="i"
    v-for="i in 3"
    v-if="status(i-1)"
    v-html="'Status: ' + msg[i-1]"
  ></span>
</v-tooltip>
</template>

<script>
export default {
  name: 'status',
  props: {
    item: {
      type: Object,
      required: true
    },
    msg: {
      type: Object,
      default: () => ({
        0: 'Hidden',
        1: 'Hiring',
        2: 'On hold',
      })
    }
  },

  methods: {
    status(n) {
      return Number(this.item.status) == n
    }
  }
}
</script>
