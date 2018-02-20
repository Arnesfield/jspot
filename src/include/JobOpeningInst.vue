<template>
<v-card>
  <v-card-title primary-title class="pb-0">
    <div>
      <div class="headline">{{ item.title }}</div>
      <div class="subheading">{{ $wrap.date(item.dateFrom) + ' to ' + $wrap.date(item.dateTo) }}</div>
      <div class="subheading">{{ $wrap.HMSToHM(item.timeFrom) + ' to ' + $wrap.HMSToHM(item.timeTo) }}</div>
      <div class="grey--text">{{ item.description }}</div>
    </div>
  </v-card-title>

  <v-card-actions>
    <div>
      <v-tooltip top>
        <v-btn icon slot="activator">
          <v-icon color="grey">info</v-icon>
        </v-btn>
        <span>View detailed</span>
      </v-tooltip>
      <v-tooltip top v-if="$bus.session.user.id == item.created_by">
        <v-btn icon slot="activator">
          <v-icon color="grey">edit</v-icon>
        </v-btn>
        <span>Edit</span>
      </v-tooltip>
      <v-tooltip top v-if="$bus.session.user.id == item.created_by">
        <v-btn icon slot="activator" @click="deleteItem(item)">
          <v-icon color="grey">delete</v-icon>
        </v-btn>
        <span>Delete</span>
      </v-tooltip>
    </div>
    <v-spacer/>
    <status :item="item"/>
    <v-tooltip top v-if="this.$bus.session.user.id != item.created_by">
      <v-btn icon slot="activator">
        <v-icon color="primary">arrow_forward</v-icon>
      </v-btn>
      <span>Apply</span>
    </v-tooltip>
  </v-card-actions>
</v-card>
</template>

<script>
import Status from '@/include/Status'

export default {
  name: 'job-opening-inst',
  components: {
    Status
  },
  props: {
    item: {
      type: Object,
      default: null
    }
  },

  methods: {
    deleteItem(item) {
      this.$bus.$emit('dialog--delete.show', {
        item: item,
        title: 'Delete Job',
        subtitle: 'Job ID: ' + item.id,
        msg: '<div class="body-1">Are you sure you want to delete this job?</div>',
        fn: (onSuccess, onError, close, fn) => {
          this.$http.post('/jobs/delete', qs.stringify({
            id: item.id
          })).then((res) => {
            console.error(res.data)
            if (!res.data.success) {
              throw new Error('Request failure.')
            }
            this.$bus.$emit('snackbar--show', 'Job deleted successfully.')
            // update job openings
            this.$bus.$emit('update--my-job-openings');
            onSuccess()
          }).catch(e => {
            console.error(e)
            this.$bus.$emit('snackbar--show', {
              text: 'Unable to delete job.',
              btns: {
                text: 'Retry',
                icon: false,
                color: 'accent',
                cb: (sb, e) => {
                  // fn(onSuccess, onError, close, fn)
                  this.deleteItem(item)
                  sb.snackbar = false
                }
              }
            })
            onError()
            close()
          })
        }
      })
    }
  }
}
</script>
