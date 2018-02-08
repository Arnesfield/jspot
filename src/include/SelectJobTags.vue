<template>
  <v-select
    v-model="tags"
    label="Jobs"
    chips
    tags
    deletable-chips
    autocomplete
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
    tags: {
      type: Array
    },
    items: {
      type: Array
    }
  },
  data: () => ({
    url: '/tags',
    search: null,
    loading: false
  }),
  watch: {
    tags(to, from) {
      this.$emit('update-job-tags', to)
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
