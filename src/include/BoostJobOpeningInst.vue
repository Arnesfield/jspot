<template>
<v-card
  class="pa-0"
  style="overflow: hidden"
>
  <v-card-text style="padding: 8px !important">
    <v-layout>
      <v-flex
        style="width: 24px"
        :style="color ? { 'background-color': color } : undefined"
        :class="{ primary: typeof color === 'undefined' || color === null }"
      />

      <div class="pr-1 full-width">
        <v-card-title
          class="title pb-2"
        >{{ item.title }}</v-card-title>
        <v-card-text class="py-0">
          <div class="grey--text text--darken-1">
            <div>{{ item.description }}</div>
            <v-divider class="mt-2"/>
            <div class="subheading mt-2">
              <template v-if="isBoosted">
                <template>You have boosted this job! You can boost it again after</template>
                <strong>{{ $wrap.datetime(item.b_ends_at, false, true) }}</strong>.
              </template>
              <template v-else>
                <div>Boost for 24 hours for only <strong>$0.99</strong>!</div>
              </template>
            </div>
          </div>
        </v-card-text>

        <v-card-actions class="py-3 px-2">
          <v-spacer/>
          <v-btn
            flat
            @click="$emit('view', item, true)"
          >Info</v-btn>
          <v-btn
            :color="isBoosted ? 'success' : 'warning'"
            @click="clickBoost"
            :disabled="loading"
            :loading="loading"
          >
            <template v-if="isBoosted">
              <v-icon>check</v-icon>
              <span>Boosted</span>
            </template>
            <template v-else>Boost</template>
          </v-btn>
        </v-card-actions>
      </div>

    </v-layout>
  </v-card-text>

  <v-dialog
    v-model="dialog"
    transition="fade-transition"
    max-width="280"
    :persistent="loading"
  >
    <v-card>
      <v-progress-linear
        color="warning"
        height="3"
        :active="loading"
        indeterminate
        class="ma-0"
      />
      <v-card-title
        primary-title
        class="title"
      >Confirm Boost</v-card-title>
      <v-card-text class="pt-0 pb-1">This will boost <strong v-text="item.title"/> up in the dashboard.</v-card-text>
      <v-card-actions>
        <v-spacer/>
        <v-btn
          flat
          :disabled="loading"
          @click="dialog = false"
        >Cancel</v-btn>
        <v-btn
          flat
          color="warning"
          :disabled="loading"
          @click="submit"
        >Boost</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</v-card>
</template>

<script>
import qs from 'qs'
import truncate from '@/assets/js/truncate'

export default {
  name: 'boost-job-opening-inst',
  props: {
    item: Object,
    color: {
      type: [Object, String],
      default: undefined
    }
  },
  data: () => ({
    url: '/boost',
    dialog: false,
    loading: false
  }),
  computed: {
    isBoosted() {
      return typeof this.item.b_ends_at !== 'undefined'
    }
  },
  methods: {
    clickBoost() {
      if (!this.isBoosted) {
        this.dialog = true
      }
    },
    submit() {
      let errorCb = () => {
        this.$bus.$emit('snackbar--show', {
          text: 'Unable to boost job.',
          btns: {
            text: 'Retry',
            icon: false,
            color: 'accent',
            cb: (sb, e) => {
              sb.snackbar = false
              this.submit()
            }
          }
        })
      }

      this.loading = true
      this.$http.post(this.url, qs.stringify({
        id: this.item.id,
        name: 'jobs'
      })).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.dialog = false
        this.$emit('boost', () => {
          this.$bus.$emit('snackbar--show', {
            text: 'Boosted job: <strong>' + truncate.apply(this.item.title, [20, true]) +  '</strong>!',
            btns: {
              text: 'View',
              icon: false,
              color: 'accent',
              cb: (sb, e) => {
                sb.snackbar = false
                this.$router.push('/dashboard')
              }
            }
          })
        }, errorCb)
      }).catch(e => {
        console.error(e)
        errorCb()
        this.loading = false
        this.dialog = false
      })
    }
  }
}
</script>
