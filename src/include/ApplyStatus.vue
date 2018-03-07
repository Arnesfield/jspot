<template>
<v-tooltip top>
  <v-btn v-if="!icon" icon slot="activator">
    <v-icon color="error" v-if="status(0)">cancel</v-icon>
    <v-icon color="grey" v-else-if="status(1)">offline_pin</v-icon>
    <v-icon color="primary" v-else-if="status(2)">offline_pin</v-icon>
    <v-icon color="success" v-else-if="status(3)">check_circle</v-icon>
  </v-btn>
  <template v-else slot="activator">
    <v-icon color="error" v-if="status(0)">cancel</v-icon>
    <v-icon v-else-if="status(1)">offline_pin</v-icon>
    <v-icon color="primary" v-else-if="status(2)">offline_pin</v-icon>
    <v-icon color="success" v-else-if="status(3)">check_circle</v-icon>
  </template>
  <span
    :key="i"
    v-for="i in 4"
    v-if="status(i-1)"
    v-html="'Status: ' + msg[i-1]"
  ></span>
</v-tooltip>
</template>

<script>
export default {
  name: 'apply',
  props: {
    item: {
      type: Object,
      required: true
    },
    icon: {
      type: Boolean,
      default: false
    },
    msg: {
      type: Object,
      default: () => ({
        0: 'Denied',
        1: 'Pending',
        2: 'Accepted',
        3: 'Hired'
      })
    }
  },

  methods: {
    status(n) {
      return Number(this.item.a_status) == n
    }
  }
}
</script>
