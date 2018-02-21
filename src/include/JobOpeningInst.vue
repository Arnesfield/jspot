<template>
<v-card>
  <v-card-title primary-title class="pb-0">
    <div>
      <div class="headline mb-1">{{ item.title }}</div>
      <div class="grey--text">{{ item.description }}</div>
      <v-list dense>
        
        <!-- job -->

        <v-list-tile>
          <v-list-tile-action class="thin-action">
            <v-tooltip top>
              <v-icon slot="activator">person</v-icon>
              <span>Employer name</span>
            </v-tooltip>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title
              v-text="item.creator_fname + ' ' + item.creator_lname"
            />
          </v-list-tile-content>
        </v-list-tile>

        <!-- date -->
        
        <v-list-tile>
          <v-list-tile-action class="thin-action">
            <v-tooltip top>
              <v-icon slot="activator">date_range</v-icon>
              <span>Work date</span>
            </v-tooltip>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title
              v-text="$wrap.date(item.dateFrom) + ' to ' + $wrap.date(item.dateTo)"
            />
          </v-list-tile-content>
        </v-list-tile>

        <!-- time -->

        <v-list-tile>
          <v-list-tile-action class="thin-action">
            <v-tooltip top>
              <v-icon slot="activator">access_time</v-icon>
              <span>Work hours</span>
            </v-tooltip>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title
              v-text="$wrap.HMSToHM(item.timeFrom) + ' to ' + $wrap.HMSToHM(item.timeTo)"
            />
          </v-list-tile-content>
        </v-list-tile>

        <!-- age group -->

        <v-list-tile>
          <v-list-tile-action class="thin-action">
            <v-tooltip top>
              <v-icon slot="activator">people</v-icon>
              <span>Age group</span>
            </v-tooltip>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title
              v-text="'Ages ' + $wrap.ageGroup(item.age_group)"
            />
          </v-list-tile-content>
        </v-list-tile>

      </v-list>
      <div class="subheading"></div>
      <div>
        <v-chip :key="i-1" v-for="i in (item.job_tags.length > 10 ? 10 : item.job_tags.length)">
          {{ item.job_tags[i-1] }}
        </v-chip>
      </div>
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
    <v-tooltip top v-if="
      $bus.session.user.id != item.created_by
      && item.status == 1
    ">
      <v-btn icon slot="activator">
        <v-icon color="primary">arrow_forward</v-icon>
      </v-btn>
      <span>Apply</span>
    </v-tooltip>
  </v-card-actions>
</v-card>
</template>

<script>
import qs from 'qs'
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
        subtitle: 'Job title: <strong>' + item.title + '</strong>',
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

<style scoped>
.thin-action {
  width: 42px;
  min-width: 42px;
}
</style>
