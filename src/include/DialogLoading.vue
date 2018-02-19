<template>
<span style="display: flex" v-if="loading">
  <v-progress-circular
    indeterminate
    :active="loading"
    color="grey"
  />
  <span style="height: auto" class="subheader px-2">Loading...</span>
</span>
</template>

<script>
export default {
  name: 'dialog-loading',
  props: {
    loading: Boolean
  }
}
</script>
