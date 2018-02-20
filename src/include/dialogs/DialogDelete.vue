<template>
<v-dialog
  v-model="$bus.dialog.global.delete"
  transition="fade-transition"
  :persistent="loading"
  max-width="360"
>
  <v-card>

    <v-card-title primary-title>
      <div>
        <div class="headline">{{ title }}</div>
        <div class="subheading" v-html="subtitle"></div>
      </div>
    </v-card-title>

    <v-card-text v-html="msg"/>
    <v-card-actions>
      <dialog-loading :loading="loading"/>
      <v-spacer/>
      <v-btn
        flat
        :disabled="loading"
        @click="doClose"
        @keypress.enter="doClose"
      >Cancel</v-btn>
      <v-btn
        color="error"
        flat
        :disabled="loading"
        @click="doDelete"
        @keypress.enter="doDelete"
      >Delete</v-btn>
    </v-card-actions>

  </v-card>
</v-dialog>
</template>

<script>
import DialogLoading from '@/include/DialogLoading'

export default {
  name: 'dialog-delete',
  components: {
    DialogLoading
  },
  data: () => ({
    item: null,
    title: null,
    subtitle: null,
    msg: null,
    fn: null,
    loading: false
  }),
  created() {
    this.$bus.$on('dialog--global.delete', (to, from) => {
      // if closed, reset
      if (!to) {
        this.item = null
      }
    })

    this.$bus.$on('dialog--delete.show', (props) => {
      Object.keys(props).forEach(e => {
        this[e] = props[e] ? props[e] : null
      })
      this.$bus.dialog.global.delete = true
    })
  },

  methods: {
    doDelete() {
      if (typeof this.fn === 'function') {
        this.loading = true
        this.fn(this.onSuccess, this.onError, this.doClose, this.fn)
      }
    },
    doClose(e) {
      e = typeof e === 'boolean' ? !e : false
      this.$bus.dialog.global.delete = e
    },
    onSuccess() {
      this.loading = false
      this.doClose()
    },
    onError() {
      this.loading = false
    }
  }
}
</script>
