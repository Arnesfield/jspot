<template>
<v-card>
  <v-layout>
    <v-flex
      hidden-xs-only
      hidden-sm-only
      lg4
      class="primary"
    ></v-flex>
    <v-flex>
      <v-form>
        <v-card class="elevation-0">
          <v-card-title primary-title>
            <h3 class="display-1 grey--text text--darken-1">Boost your profile!</h3>
          </v-card-title>
          <v-card-text class="py-0">
            <div class="mx-3 grey--text text--darken-1">
              <div>Let others find you by being on promoted on top of the dashboard!</div>
              <div>Boost for 24 hours for only <strong>$0.99</strong>!</div>
              <template v-if="isBoosted">
                <v-divider class="mt-2"/>
                <div class="subheading mt-2">
                  <template>You have boosted! You can boost again after</template>
                  <strong>{{ endsAt }}</strong>.
                </div>
              </template>
            </div>
          </v-card-text>
          <v-card-actions class="py-3 px-2">
            <!-- <v-spacer/> -->
            <v-btn
              color="warning"
              @click="dialog = true"
              :disabled="loading || !allow"
              :loading="loading"
            >
              <template v-if="isBoosted">Boosted</template>
              <template v-else>Boost</template>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-flex>
  </v-layout>

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
      <v-card-text class="pt-0 pb-1">This will boost your profile up in the dashboard.</v-card-text>
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
export default {
  name: 'profile-boost-form',
  data: () => ({
    url: '/boost',
    checkUrl: '/boost/profileCheck',
    allow: false,
    endsAt: null,
    checkLoading: false,
    loading: false,
    dialog: false
  }),
  computed: {
    isBoosted() {
      return !this.allow && this.endsAt
    }
  },
  created() {
    this.$bus.$on('update--boosts', this.fetch)
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('update--boosts', this.fetch)
  },
  methods: {
    fetch(successCb) {
      this.checkLoading = true
      this.$http.post(this.checkUrl).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.allow = res.data.allow
        this.endsAt = res.data.endsAt ? res.data.endsAt : null
        this.checkLoading = false
        if (typeof successCb === 'function') {
          successCb()
        }
      }).catch(e => {
        console.error(e)
        this.checkLoading = false
      })
    },
    submit() {
      this.loading = true
      this.$http.post(this.url).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.dialog = false
        this.fetch(() => {
          this.$bus.$emit('snackbar--show', {
            text: 'Boosted your profile!',
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
        })
      }).catch(e => {
        console.error(e)
        this.$bus.$emit('snackbar--show', {
          text: 'Unable to boost your profile.',
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
        this.loading = false
        this.dialog = false
      })
    }
  }
}
</script>
