<template>
<v-dialog
  v-model="show"
  transition="fade-transition"
  scrollable
  :persistent="loading"
  max-width="640"
>
  <v-card v-if="job">

    <!-- toolbar -->
    <!-- slot="extension" -->
    <v-progress-linear
      color="warning"
      height="3"
      :active="loading"
      indeterminate
      class="ma-0 primary lighten-1"
    />
    <v-layout class="primary lighten-1" style="min-height: 48px">
      <v-btn
        dark
        icon
        :disabled="loading"
        @click.native="show = false"
        @keypress.enter="show = false"
      >
        <v-icon>close</v-icon>
      </v-btn>
      <v-tabs
        dark
        v-model="tabs"
        color="primary lighten-1"
      >
        <v-tab :disabled="loading">Info</v-tab>
        <v-tab :disabled="loading" v-if="!viewOnly">Apply</v-tab>
        <v-tab :disabled="loading" v-if="viewApplyMode">Application</v-tab>
        <v-tab
          :disabled="loading"
          v-if="$bus.session.user && $bus.session.user.id == job.created_by"
        >Applicants: {{ applicants.length }}</v-tab>
      </v-tabs>
    </v-layout>
    <!-- end of toolbar -->

    <!-- content -->

    <v-card-text>

      <v-tabs-items v-model="tabs">
        <v-tab-item>
          <job-details :job="job"/>
        </v-tab-item>
        <v-tab-item v-if="!viewOnly">
          <apply-form
            ref="form"
            class="px-4"
            :load="loading"
            @loading="(e) => { loading = e }"
            @close="onClose"
          />
        </v-tab-item>
        <v-tab-item v-if="viewApplyMode">
          <apply-details :item="job"/>
        </v-tab-item>
        <v-tab-item v-if="$bus.session.user && $bus.session.user.id == job.created_by">
          <manage-no-data
            :loading="fetchLoading"
            :fetch="fetchApplicants"
            msg="No job applicants :("
            v-if="!applicants.length"
          >
            <div slot="icon" class="mb-3">
              <v-icon size="64px">people</v-icon>
            </div>
          </manage-no-data>

          <template v-else>
            <v-list
              dense
              two-line
            >
              <template v-for="(a, i) in applicants">
                <v-list-tile
                  ripple
                  :key="'tile-' + i"
                  @click="viewApply(a)"
                >
                  <v-list-tile-action>
                    <v-avatar
                      size="24"
                      v-if="imgSrc(a) && imgSrc(a).isImg"
                      class="elevation-1"
                    >
                      <img :src="$wrap.localImg(imgSrc(a).text)">
                    </v-avatar>
                    <v-icon v-else>person</v-icon>  
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title
                      v-text="a.applier_fname + ' ' + a.applier_lname"
                    />
                    <v-list-tile-sub-title
                      v-text="$wrap.datetime(a.a_created_at, true, true)"
                    />
                  </v-list-tile-content>
                  <v-list-tile-action>
                    <apply-status :item="a"/>
                  </v-list-tile-action>
                </v-list-tile>
                <v-divider
                  :key="'divider-' + i"
                  v-if="applicants.length-1 != i"
                />
              </template>
            </v-list>
          </template>

        </v-tab-item>
      </v-tabs-items>

    </v-card-text>

    <!-- end of content -->

    <v-divider/>
    <v-card-actions class="px-2">
      <v-spacer/>
      <v-btn
        flat
        tabindex="0"
        v-if="tabs == '0'"
        :disabled="loading"
        @click="show = false"
        @keypress.enter="show = false"
        v-text="'Cancel'"
      />
      <v-btn
        flat
        tabindex="0"
        v-if="tabs == '1'"
        :disabled="loading"
        @click="tabs = '0'"
        @keypress.enter="tabs = '0'"
        v-text="'Previous'"
      />
      <template v-if="viewApplyMode">
        <v-btn
          flat
          tabindex="0"
          color="primary"
          v-if="tabs == '0'"
          :disabled="loading"
          @click="tabs = '1'"
          @keypress.enter="tabs = '1'"
          v-text="'Next'"
        />
        <v-btn
          tabindex="0"
          color="error"
          v-if="$bus.session.user && $bus.session.user.id == job.a_user_id && tabs == '1'"
          :disabled="loading"
          @click="clickDelete"
          @keypress.enter="clickDelete"
          v-text="'Delete'"
        />
      </template>
      <template v-if="!viewOnly">
        <v-btn
          :flat="tabs == '0'"
          color="primary"
          tabindex="0"
          :disabled="loading"
          @click="clickPrimary"
          @keypress.enter="clickPrimary"
          v-text="tabs == '0' ? 'Next' : 'Apply'"
        />
      </template>
    </v-card-actions>

  </v-card>

  <dialog-apply-action/>

</v-dialog>
</template>

<script>
import qs from 'qs'
import ApplyForm from '@/include/forms/ApplyForm'
import JobDetails from '@/include/JobDetails'
import ApplyDetails from '@/include/ApplyDetails'
import ManageNoData from '@/include/ManageNoData'
import DialogApplyAction from './DialogApplyAction'
import ApplyStatus from '@/include/ApplyStatus'

export default {
  name: 'dialog-job-opening',
  components: {
    ApplyForm,
    JobDetails,
    ApplyDetails,
    ManageNoData,
    DialogApplyAction,
    ApplyStatus
  },
  data: () => ({
    url: '/apply/applicants',
    show: false,
    job: null,
    tabs: '0',
    viewOnly: false,
    viewApplyMode: false,
    loading: false,
    fetchLoading: false,
    applicants: []
  }),

  watch: {
    show(to, from) {
      // if exit, clear
      if (!to) {
        this.clear()
        return
      }

      // check if sess is this
      if (this.$bus.session.user && this.$bus.session.user.id == this.job.created_by) {
        this.fetchApplicants()
      }
    }
  },

  created() {
    this.$bus.$on('dialog--job.apply', (job, viewOnly, viewApplyMode) => {
      if (typeof viewOnly !== 'boolean') {
        viewOnly = false
      }
      if (typeof viewApplyMode !== 'boolean') {
        viewApplyMode = false
      }
      this.job = job
      this.viewOnly = viewOnly
      this.viewApplyMode = viewApplyMode
      if (this.viewApplyMode) {
        this.tabs = '1'
      }
      this.show = true
    })
    this.$bus.$on('dialog--job-apply.update-applicants', this.fetchApplicants)
  },

  methods: {
    clickDelete() {
      this.$emit('delete', this.job)
    },

    clickPrimary() {
      // check tabs
      if (this.tabs == '0') {
        this.tabs = '1'
        return
      }
      this.submit()
    },
    onClose() {
      this.show = false
      this.$emit('success')
    },

    submit() {
      this.$refs.form.apply(this.job)
    },
    clear() {
      if (this.$refs.form) {
        this.$refs.form.clear()
      }

      this.job = null
      this.viewOnly = false
      this.viewApplyMode = false
      this.tabs = '0'
      this.applicants = []
      this.loading = false
      this.fetchLoading = false
    },
    close() {
      this.clear()
      this.show = false
    },

    viewApply(a) {
      this.$bus.$emit('dialog--apply-action.show', a)
    },

    fetchApplicants() {
      this.loading = true
      this.fetchLoading = true
      this.$http.post(this.url, qs.stringify({
        jid: this.job.id
      })).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.applicants = res.data.applicants
        this.loading = false
        this.fetchLoading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
        this.fetchLoading = false
      })
    },

    imgSrc(user) {
      if (typeof user !== 'object' || user === null) {
        return null
      }
      if (typeof user.applier_img_src !== 'string' || !user.applier_img_src.length) {
        return {
          isImg: false,
          text: user.applier_fname.charAt(0).toUpperCase()
        }
      } 
      return {
        isImg: true,
        text: user.applier_img_src
      }
    }
  }
}
</script>
