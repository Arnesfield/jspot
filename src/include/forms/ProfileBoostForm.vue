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
            <div
              class="mx-3 grey--text text--darken-1"
            >
              <div>Let others find you by being on promoted on top of the dashboard!</div>
              <div>Boost for 24 hours for only <strong>$0.99</strong>!</div>
              <template v-if="!allow && endsAt">
                <v-divider class="mt-2"/>
                <div class="subheading mt-2">
                  <template>You have boosted! Boost again after</template>
                  <strong>{{ endsAt }}</strong>.
                </div>
              </template>
            </div>
          </v-card-text>
          <v-card-actions class="py-3 px-2">
            <!-- <v-spacer/> -->
            <v-btn
              color="warning"
              @click="submit"
              :disabled="loading || !allow"
              :loading="loading"
            >Boost</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-flex>
  </v-layout>
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
    loading: false
  }),
  created() {
    this.fetch()
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
      })
    }
  }
}
</script>
