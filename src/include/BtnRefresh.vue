<template>
  <v-tooltip
    :left="tip === 'left'"
    :right="tip === 'right'"
    :top="tip === 'top'"
    :bottom="tip === 'bottom'"
  >
    <v-btn
      icon
      @click="refreshClick"
      slot="activator"
    >
      <v-progress-circular
        indeterminate
        :width="6"
        :size="18"
        v-if="refresh"
      />
      <v-icon v-else>refresh</v-icon>
    </v-btn>
    <span v-if="refresh">Refreshing</span>
    <span v-else>Refresh</span>
  </v-tooltip>
</template>

<script>
export default {
  name: 'btn-refresh',
  props: {
    click: [String, Array],
    refresh: Boolean,
    tip: {
      type: String,
      default: 'left'
    }
  },
  methods: {
    refreshClick() {
      if (typeof this.click === 'string') {
        this.$bus.$emit(this.click)
      } else {
        this.click.forEach(e => {
          this.$bus.$emit(e)
        })
      }
    }
  }
}
</script>
