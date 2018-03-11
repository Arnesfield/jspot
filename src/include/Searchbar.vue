<template>
<v-text-field
  flat
  prepend-icon="search"
  append-icon="close"
  :append-icon-cb="clearInput"
  label="Search"
  class="mx-3"
  solo-inverted
  v-model="input"
  @keypress.enter="search('enter')"
/>
</template>

<script>
import debounce from 'lodash/debounce'

export default {
  name: 'searchbar',
  data: () => ({
    input: null
  }),
  watch: {
    $route(to, from) {
      // only clear if to and from not dashboard
      if (to.name !== 'Dashboard' && from.name !== 'Dashboard') {
        this.input = null
      }
    },
    input(e) {
      // show progress when in dashboard only
      if (this.$route.name === 'Dashboard') {
        this.$bus.progress.active = true
      }
      this.$bus.search.input = e
      this.search()
    }
  },

  methods: {
    clearInput() {
      this.input = null
    },
    search: debounce(function(e) {
      if (typeof e !== 'string') {
        e = 'input'
      }

      // only search if input search exists
      if (!this.input) {
        // nevermind lol
        // return
      }

      // go to dashboard on enter search
      if (e === 'enter' && this.$route.name !== 'Dashboard') {
        this.$router.push('/dashboard')
      }

      // emit search
      this.$bus.$emit('search', this.input)
    }, 500)
  }
}
</script>
