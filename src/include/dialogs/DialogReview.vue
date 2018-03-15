<template>
<v-dialog
  v-model="show"
  transition="fade-transition"
  scrollable
  :persistent="true"
  max-width="640"
>
  <v-card v-if="id !== null">
    <v-toolbar dark flat color="primary lighten-1">
      <v-btn
        dark
        icon
        :disabled="loading"
        @click.native="show = false"
      >
        <v-icon>close</v-icon>
      </v-btn>
      <v-toolbar-title v-text="'Submit a review'"/>
    </v-toolbar>

    <v-progress-linear
      color="warning"
      height="3"
      :active="loading"
      indeterminate
      background-color="primary lighten-1"
      class="ma-0 transparent"
    />

    <v-card-text>
      <v-form ref="form" v-model="form">

        <v-text-field
          label="Message body"
          placeholder="Enter message body"
          v-model="body"
          :disabled="loading"
          prepend-icon="message"
          :rules="[$vfRule('required')]"
          class="multi-line-textarea"
          required
          multi-line
        />

        <div
          class="mt-1"
          style="display: flex"
        >
          <div>
            <div class="subheading mt-4">
              <v-icon>star</v-icon>
            </div>
          </div>
          <div class="ml-3">
            <div
              class="caption"
              :class="{
                'red--text text--accent-2': ratingError,
                'grey--text text--darken-1': !ratingError
              }"
            >Rating*</div>
            <star-rating
              v-model="rating"
              :star-size="32"
              :inactive-color="ratingError ? '#FF5252' : undefined"
            />
          </div>
        </div>

      </v-form>
    </v-card-text>

    <v-divider/>
    <v-card-actions>
      <v-spacer/>
      <v-btn
        flat
        @click="show = false"
        :disabled="loading"
      >Cancel</v-btn>
      <v-btn
        color="primary"
        :disabled="loading"
        @click="submit"
      >Submit</v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>
</template>

<script>
import qs from 'qs'
import StarRating from 'vue-star-rating'

export default {
  name: 'dialog-review',
  components: {
    StarRating
  },
  data: () => ({
    url: '/reviews/review',
    form: false,
    id: null,
    body: null,
    rating: 0,
    ratingError: false,
    show: false,
    loading: false
  }),
  watch: {
    rating(e) {
      if (e > 0) {
        this.ratingError = false
      }
    },
    show(e) {
      // if not show
      if (!e) {
        this.id = null
        this.clear()
      }
    }
  },
  created() {
    this.$bus.$on('dialog--review', this.onCalled)
  },
  beforeDestroy() {
    this.$bus.$off('dialog--review', this.onCalled)
  },
  methods: {
    onCalled(id) {
      this.id = id
      this.show = true
    },

    submit() {
      if (this.rating == 0) {
        this.ratingError = true
      }

      if (!this.$refs.form.validate()) {
        this.loading = false
        return
      }

      if (this.ratingError) {
        return
      }

      this.loading = true
      this.$http.post(this.url, qs.stringify({
        id: this.id,
        body: this.body,
        rating: this.rating
      })).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.show = false
        this.loading = false
        this.$bus.$emit('snackbar--show', 'Review has been submitted.')
        this.$bus.$emit('update--reviews')
        this.$bus.$emit('update--ratings-graph')
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },

    clear() {
      if (this.$refs.form) {
        this.$refs.form.reset()
      }

      this.body = null
      this.rating = 0
      this.ratingError = false
      this.show = false
      this.loading = false
    }
  }
}
</script>
