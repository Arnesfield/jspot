<template>
<span>
  <v-icon
    class="pa-0"
    :key="i"
    v-for="i in max"
    style="font-size: 12px"
    :color="i <= value ? 'grey darken-1' : 'grey lighten-1'"
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
    }
  }
}
</script>
