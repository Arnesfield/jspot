<template>
<v-tooltip top>
  <v-btn icon slot="activator">
    <v-icon color="error" v-if="status(0)">cancel</v-icon>
    <v-icon color="success" v-else-if="status(1)">check_circle</v-icon>
  </v-btn>
  <span
    :key="i"
    v-for="i in 4"
    v-if="status(i-1)"
    v-html="msg[i-1]"
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
        0: 'Active',
        1: 'Hidden'
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
