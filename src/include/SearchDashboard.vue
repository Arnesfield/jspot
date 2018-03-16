<template>
<v-container
  :style="!combined.length ? {
    'height': 'calc(100% - 64px)',
    'display': 'flex'
  } : null"
  grid-list-lg
>

  <template v-if="combined.length">

    <v-layout row wrap>
      <slot name="col"/>
      <v-flex
        xs12
        sm12
        md6
        lg4
        :key="i"
        v-for="i in maxCol"
      >

        <v-subheader
          v-if="i == 1 && ($bus.search.input || $bus.session.user)"
          :key="'subheader-' + i"
        >
          <template
            v-if="i == 1 && $bus.search.input"
          >Showing {{ combined.length }} results for:&nbsp;<strong>{{ $bus.search.input }}</strong>
          </template>
          <template v-else-if="i == 1 && $bus.session.user">Suggested</template>
        </v-subheader>

        <template v-for="(item) in indexCheck(i)">
          <job-opening-inst
            v-if="item.title"
            slim
            :item="item"
            :key="'job-' + item.id"
            class="mb-2 pa-0"
            @view="viewJob"
            @apply="apply"
          />
          <user-inst
            :key="'user-' + item.id"
            v-else-if="item.fname"
            class="mb-2 pa-0"
            :item="item"
          />
        </template>
      </v-flex>
    </v-layout>

  </template>

  <v-layout
    v-else
    justify-center
    align-center
  >
    <manage-no-data
      :loading="loading"
      :fetch="fetch"
      :msg="
        typeof $bus.search.input === 'string'
          ? 'No results for: <strong>' + $bus.search.input + '</strong> :('
          : 'Nothing to show :('
      "
    >
      <div slot="icon" class="mb-3">
        <v-icon size="64px">search</v-icon>
      </div>
    </manage-no-data>
  </v-layout>

  <dialog-job-apply/>

</v-container>
</template>

<script>
import qs from 'qs'
import createCombined from '@/assets/js/createCombined'
import DialogJobApply from '@/include/dialogs/DialogJobApply'
import JobOpeningInst from '@/include/JobOpeningInst'
import UserInst from '@/include/UserInst'
import ManageNoData from '@/include/ManageNoData'

export default {
  name: 'search-dashboard',
  components: {
    DialogJobApply,
    JobOpeningInst,
    UserInst,
    ManageNoData
  },
  data: () => ({
    url: '/search',
    jobs: [],
    users: [],
    combined: [],
    loading: false,
    maxCol: 3
  }),
  watch: {
    loading(e) {
      this.$bus.progress.active = e
      this.$bus.progress.circular.Dashboard.refresh = e
    }
  },
  created() {
    this.$bus.$on('search', this.fetch)
    this.$bus.$on('update--dashboard', this.fetch)
    this.$bus.$on('change--session.auth', this.fetchWrap)
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('update--dashboard')
    this.$bus.$off('search', this.fetch)
    this.$bus.$off('change--session.auth', this.fetchWrap)
  },

  methods: {
    indexCheck(n) {
      return this.combined.filter((e, i) => {
        while (i >= this.maxCol) {
          i -= this.maxCol
        }
        return n-1 === i
      })
    },
    fetchWrap() {
      this.fetch()
    },
    fetch(search) {
      if (typeof search !== 'string' && !search) {
        if (typeof this.$bus.search.input === 'string') {
          search = this.$bus.search.input
        } else {
          search = ''
        }
      }

      if (typeof search !== 'string') {
        search = ''
      }

      this.loading = true
      this.$http.post(this.url, qs.stringify({
        search: search
      })).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.jobs = res.data.jobs
        this.users = res.data.users
        this.combined = createCombined(this.jobs, this.users)
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    },
    fetchedBoosted(combined) {
      this.maxCol = combined.length == 0 ? 3 : 2
    },

    apply(job) {
      this.$bus.$emit('dialog--job.apply', job)
    },
    viewJob(job, viewOnly) {
      this.$bus.$emit('dialog--job.apply', job, viewOnly)
    }
  }
}
</script>
