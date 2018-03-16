<template>
<v-layout row wrap>
  <v-flex
    xs12
    sm12
    md6
    lg4
    :key="i"
    v-for="(item, i) in reviews"
    class="pt-0"
  >
    <review-inst
      :key="'review-' + i"
      :item="item"
      to
    />
  </v-flex>
</v-layout>
</template>

<script>
import ReviewInst from '@/include/ReviewInst'

export default {
  name: 'home-reviews',
  components: {
    ReviewInst
  },
  data: () => ({
    url: '/home/getReviews',
    reviews: [],
    loading: false
  }),
  created() {
    this.fetch()
  },
  methods: {
    fetch() {
      this.$http.post(this.url).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Exception('Request failure.')
        }
        this.reviews = res.data.reviews
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
