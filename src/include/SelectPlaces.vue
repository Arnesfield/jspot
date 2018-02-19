<template>
  <v-select
    v-model="places"
    :label="label"
    :placeholder="placeholder"
    chips
    tags
    deletable-chips
    autocomplete
    :disabled="disabled"
    :search-input.sync="search"
    debounce-search
    prepend-icon="place"
    :loading="loading"
    :items="items"
    @update:searchInput="fetch"
  ></v-select>
</template>

<script>
import qs from 'qs'

export default {
  name: 'select-places',
  props: {
    value: Array,
    disabled: Boolean,
    label: {
      type: String,
      default: 'Places'
    },
    placeholder: {
      type: String,
      default: undefined
    }
  },
  data: () => ({
    url: '/places',
    search: null,
    places: [],
    items: [],
    loading: false
  }),
  watch: {
    places(to, from) {
      this.$emit('input', to)
    },
    value(e) {
      this.places = e
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
        this.items = res.data.places
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
