<template>
<v-container
  :style="!reviews.length || true ? {
    'height': '100%',
  } : null"
  grid-list-lg
>

  <template v-if="reviews.length">
    <ratings-graph :id="user ? user.id : null"/>
    <v-subheader class="mt-2">User reviews</v-subheader>
    <v-layout row wrap>
      <v-flex
        xs12
        sm12
        md6
        lg4
        :key="i"
        v-for="i in maxCol"
        class="pt-0"
      >
        <template v-for="(item, j) in indexCheck(i)">
          <review-inst
            :key="'review-' + i + '-' + j"
            :item="item"
            @delete="deleteReview(item)"
          />
        </template>
      </v-flex>
    </v-layout>
  </template>

  <v-layout
    v-else
    justify-center
    align-center
    style="height: 100%"
  >
    <manage-no-data
      :loading="loading"
      :fetch="fetch"
      msg="No reviews yet :("
    >
      <div slot="icon" class="mb-3">
        <v-icon size="64px">rate_review</v-icon>
      </div>
    </manage-no-data>
  </v-layout>

</v-container>
</template>

<script>
import qs from 'qs'
import ReviewInst from '@/include/ReviewInst'
import RatingsGraph from '@/include/RatingsGraph'
import ManageNoData from '@/include/ManageNoData'

export default {
  name: 'reviews',
  components: {
    ReviewInst,
    RatingsGraph,
    ManageNoData
  },
  props: {
    user: {
      type: Object,
      default: null
    },
    value: Boolean
  },
  data: () => ({
    url: '/reviews',
    deleteUrl: '/reviews/delete',
    reviews: [],
    loading: false,
    maxCol: 3
  }),

  watch: {
    user(e) {
      this.fetch()
    },
    reviews: {
      deep: true,
      handler(e) {
        this.$emit('input', e.length == 0)
      }
    },
    loading(e) {
      this.$bus.progress.active = e
      this.$bus.progress.circular.Profile.refresh = e
    }
  },

  created() {
    this.$bus.$on('update--reviews', this.fetch)
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('update--reviews', this.fetch)
  },

  methods: {
    indexCheck(n) {
      return this.reviews.filter((e, i) => {
        while (i >= this.maxCol) {
          i -= this.maxCol
        }
        return n-1 === i
      })
    },

    deleteReview(item) {
      this.$bus.$emit('dialog--global.confirm.show', {
        item: item,
        title: 'Delete review',
        msg: 'This will permanently delete your review.',
        btn: {
          text: 'Delete',
          color: 'error'
        },
        fn: (onSuccess, onError, doClose, fn) => {
          this.$http.post(this.deleteUrl, qs.stringify({
            id: item.id
          })).then(res => {
            console.warn(res.data)
            if (!res.data.success) {
              throw new Error('Request failure.')
            }

            this.$bus.$emit('snackbar--show', 'Review deleted.')
            this.$bus.$emit('update--ratings-graph')
            this.fetch()
            onSuccess()
          }).catch(e => {
            console.error(e)
            this.$bus.$emit('snackbar--show', {
              text: 'Unable to delete review.',
              btns: {
                text: 'Retry',
                icon: false,
                color: 'accent',
                cb: (sb, e) => {
                  sb.snackbar = false
                  fn(onSuccess, onError, doClose, fn)
                  // this.deleteComment(item)
                }
              }
            })
            onError()
            doClose()
          })
        }
      })
    },

    fetch() {
      let id = 0
      if (this.user !== null) {
        id = this.user.id
      }

      this.loading = true
      this.$http.post(this.url, !this.user ? undefined : qs.stringify({
        id: id
      })).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
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
