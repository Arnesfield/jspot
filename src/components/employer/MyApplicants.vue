<template>
<v-container
  :style="!(jobs.length && applicants.length) ? {
    'height': 'calc(100% - 64px)',
    'display': 'flex'
  } : null"
>

  <template v-if="jobs.length && applicants.length">
    <template v-for="(job, i) in jobs" v-if="(temp = getApplicants(job.id)).length">
      <v-layout :key="'layout-' + i">
        <v-subheader class="pl-0">
          <v-tooltip top>
            <v-btn
              small
              icon
              slot="activator"
              @click="viewJob(job)"
            >
              <v-icon
                small
                color="grey"
              >info</v-icon>
            </v-btn>
            <span>Job info</span>
          </v-tooltip>
          <span>{{ job.title }}</span>
        </v-subheader>
      </v-layout>
      <v-list
        dense
        two-line
        :key="'list-' + i"
        class="mb-2 elevation-1"
      >
        <template v-for="(a, i) in temp">
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
            v-if="temp.length-1 != i"
          />
        </template>
      </v-list>
    </template>
  </template>

  <v-layout
    v-else
    justify-center
    align-center
  >
    <manage-no-data
      :loading="loading"
      :fetch="fetch"
      msg="You have no applicants :("
    >
      <div slot="icon" class="mb-3">
        <v-icon size="64px">people</v-icon>
      </div>
    </manage-no-data>
  </v-layout>

  <dialog-job-apply/>

</v-container>
</template>

<script>
import ApplyStatus from '@/include/ApplyStatus'
import ManageNoData from '@/include/ManageNoData'
import DialogJobApply from '@/include/dialogs/DialogJobApply'
// DialogApplyAction already in DialogJobApply

export default {
  name: 'my-applicants',
  components: {
    ApplyStatus,
    ManageNoData,
    DialogJobApply
  },
  data: () => ({
    url: '/apply/jobs',
    jobs: [],
    applicants: [],
    loading: false
  }),
  watch: {
    loading(e) {
      this.$bus.progress.active = e
    }
  },

  created() {
    this.$bus.$on('dialog--job-apply.update-applicants', this.fetch)
    this.fetch()
  },

  methods: {
    getApplicants(jid) {
      return this.applicants.filter(e => {
        return e.id == jid
      })
    },
    fetch() {
      this.loading = true
      this.$http.post(this.url).then(res => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.jobs = res.data.jobs
        this.applicants = res.data.applicants
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },

    viewJob(e) {
      this.$bus.$emit('dialog--job.apply', e, true, false, false)
    },
    viewApply(a) {
      this.$bus.$emit('dialog--apply-action.show', a)
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
