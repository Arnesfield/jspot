<template>
  <v-select
    v-model="tags"
    :label="label"
    :placeholder="placeholder"
    chips
    tags
    deletable-chips
    autocomplete
    :disabled="disabled"
    :search-input.sync="search"
    debounce-search
    prepend-icon="work"
    :loading="loading"
    :items="items"
    @update:searchInput="fetch"
  ></v-select>
</template>

<script>
import qs from 'qs'

export default {
  name: 'select-job-tags',
  props: {
    value: Array,
    label: {
      type: String,
      default: 'Jobs'
    },
    placeholder: {
      type: String,
      default: undefined
    },
    disabled: Boolean
  },
  data: () => ({
    url: '/tags',
    search: null,
    tags: [],
    items: [],
    loading: false
  }),
  watch: {
    tags(to, from) {
      this.$emit('input', to)
    },
    value(e) {
      this.tags = e
    },
    search(e) {
      e && this.fetch(e)
    }
  },

  methods: {
    fetch(e) {
      this.loading = true
      this.$http.post(this.url, qs.stringify({
        search: e
      })).then((res) => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.items = res.data.tags
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
