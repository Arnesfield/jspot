<template>
<span>
  <v-icon
    class="pa-0"
    :key="i"
    v-for="i in max"
    :color="color(i)"
    :style="{ color: color(i) }"
    :size="size"
  >star</v-icon>
</span>
</template>

<script>
export default {
  name: 'simple-star-rating',
  props: {
    value: [Number, String],
    max: {
      type: Number,
      default: 5
    },
    colorless: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: '12px'
    }
  },
  methods: {
    color(i) {
      if (i > this.value) {
        return 'grey lighten-1'
      } else if (this.colorless) {
        return 'grey darken-1'
      }
      switch (Number(this.value)) {
        case 1: return '#ff6f31'
        case 2: return '#ff9f02'
        case 3: return '#ffcf02'
        case 4: return '#99cc00'
        case 5: return '#88b131'
      }
    }
  }
}
</script>
