<template>
<v-form ref="form" v-model="form" enctype="multipart/form-data">

  <!-- subject -->

  <v-layout>
    <v-flex hidden-xs-only sm3>
      <v-subheader>Subject</v-subheader>
    </v-flex>
    <v-flex sm9>
      <v-text-field
        label="Enter subject"
        v-model="subject"
        :disabled="loading"
        prepend-icon="subject"
        :rules="[$vfRule('required')]"
        required
      />
    </v-flex>
  </v-layout>

  <!-- body -->
  
  <v-layout>
    <v-flex hidden-xs-only sm3>
      <v-subheader>Body</v-subheader>
    </v-flex>
    <v-flex sm9>
      <v-text-field
        label="Enter message body"
        v-model="body"
        :disabled="loading"
        prepend-icon="message"
        :rules="[$vfRule('required')]"
        class="multi-line-textarea"
        required
        multi-line
      />
    </v-flex>
  </v-layout>

  <!-- attachments -->
  
  <v-layout>
    <v-flex hidden-xs-only sm3>
      <v-subheader>
        <div>
          <div>Attachments</div>
          <div class="caption" v-text="'(jpg, png, pdf)'"/>
        </div>
      </v-subheader>
    </v-flex>
    <v-flex sm9 class="py-2 mt-1">
      <v-icon class="pr-2 mr-1">attach_file</v-icon>
      <input
        type="file"
        multiple
        :disabled="loading"
        @change="filesChange"
        :name="fileName"
      />
      <div v-if="files.length" class="mt-3">
        <div
          v-text="'Files'"
          class="caption grey--text mb-2"
        />
        <v-list
          dense
          two-line
          class="elevation-1"
        >
          <template v-for="(file, i) in files">
            <v-list-tile :key="'tile- ' + i">
              <v-list-tile-action
                class="thin-32"
              >
                <v-btn icon small>
                  <v-icon small>attach_file</v-icon>
                </v-btn>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title v-text="file.name"/>
                <v-list-tile-sub-title v-text="$wrap.fileSize(file.size)"/>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider
              :key="'divider-' + i"
              v-if="files.length-1 != i"
            />
          </template>
        </v-list>
      </div>
    </v-flex>
  </v-layout>

</v-form>
</template>

<script>
export default {
  name: 'apply-form',
  props: {
    load: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    url: '/jobs/apply',
    form: false,
    subject: null,
    body: null,
    files: [],
    fileName: 'files',
    loading: false
  }),
  watch: {
    load(e) {
      this.loading = e
    },
    loading(e) {
      this.$emit('loading', e)
    }
  },

  methods: {
    apply(job) {
      if (!job || !this.$refs.form.validate()) {
        this.loading = false
        return
      }

      this.loading = true

      let data = new FormData()
      data.append('jid', job.id)
      data.append('subject', this.subject)
      data.append('body', this.body)
      data.append('length', this.files.length)
      for (let i = 0; i < this.files.length; i++) {
        data.append('file_' + i, this.files.item(i))
      }

      this.$http.post(this.url, data).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          if (res.data.error) {
            this.$bus.$emit('snackbar--show', res.data.error)
          }
          throw new Error('Request failure.')
        }
        
        this.show = false
        this.loading = false
        this.$bus.$emit('snackbar--show', 'Your application has been sent.')
        this.$bus.$emit('update--my-job-openings');
        this.clear()
        this.$emit('close')
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },

    clear() {
      if (this.$refs.form) {
        this.$refs.form.reset()
        if (typeof this.$refs.form.$el.reset === 'function') {
          this.$refs.form.$el.reset()
        }
      }

      this.subject = null
      this.body = null
      this.files = []
      this.loading = false
    },

    filesChange(e) {
      let f = e.target
      this.files = f.files
    }
  }
}
</script>
