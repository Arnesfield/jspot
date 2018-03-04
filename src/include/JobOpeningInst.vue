<template>
<v-card class="pa-0s">
  <v-card-title class="pb-0" style="padding: 8px !important">
    <v-layout>
      <div class="grey lighten-3 pa-1" style="width: 64px">
        <status :item="item"/>
        <span>
          <v-tooltip top>
            <v-btn icon slot="activator" @click="dSlim = !dSlim">
              <v-icon v-if="dSlim" color="grey">info</v-icon>
              <v-icon v-else color="grey">info_outline</v-icon>
            </v-btn>
            <span v-if="dSlim">Peak few details</span>
            <span v-else>Unpeak details</span>
          </v-tooltip>
          <v-tooltip top v-if="isLogged && $bus.session.user.id == item.created_by">
            <v-btn icon slot="activator">
              <v-icon color="grey">edit</v-icon>
            </v-btn>
            <span>Edit</span>
          </v-tooltip>
          <v-tooltip top v-if="isLogged && $bus.session.user.id == item.created_by">
            <v-btn icon slot="activator" @click="deleteItem(item)">
              <v-icon color="grey">delete</v-icon>
            </v-btn>
            <span>Delete</span>
          </v-tooltip>
        </span>
        
        <v-tooltip top v-if="
          isLogged
          && $bus.session.user.id != item.created_by
          && $bus.session.user.type != 2
          && item.status == 1
        ">
          <v-btn
            icon
            slot="activator"
            @click="$emit('apply', item)"
            @keypress.enter="$emit('apply', item)"
          >
            <v-icon color="primary">arrow_forward</v-icon>
          </v-btn>
          <span>Apply</span>
        </v-tooltip>
        <v-tooltip top v-else-if="!isLogged && item.status == 1">
          <v-btn icon slot="activator" disabled>
            <v-icon color="primary">arrow_forward</v-icon>
          </v-btn>
          <span>Log in to apply!</span>
        </v-tooltip>
      </div>
      
      <v-flex class="pa-3">
        <div class="headline mb-1">{{ item.title }}</div>
        <div v-if="!dSlim" class="grey--text">{{ item.description }}</div>
        <v-list dense class="pb-0">
          
          <!-- job -->

          <v-list-tile>
            <v-list-tile-action class="thin-42">
              <v-tooltip top>
                <template v-if="imgSrc && imgSrc.isImg">
                  <v-avatar slot="activator" size="24">
                    <img :src="$wrap.localImg(imgSrc.text)">
                  </v-avatar>
                </template>
                <v-icon v-else slot="activator">person</v-icon>
                <span>Employer</span>
              </v-tooltip>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>
                <router-link
                  slot="activator"
                  :to="'/profile/' + item.created_by"
                  v-text="item.creator_fname + ' ' + item.creator_lname"
                />
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <!-- date -->
          
          <v-list-tile>
            <v-list-tile-action class="thin-42">
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

          <v-list-tile v-if="!dSlim">
            <v-list-tile-action class="thin-42">
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

          <v-list-tile v-if="!dSlim">
            <v-list-tile-action class="thin-42">
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

        <!-- location -->

        <v-layout class="py-2 mx-3" v-if="item.location && item.location.length">
          <div>
            <v-tooltip top>
              <v-icon slot="activator" size="21.6px">location_on</v-icon>
              <span>Age group</span>
            </v-tooltip>
          </div>
          <div class="px-3">
            <template v-for="i in totalVisibleLocation(item)">
              <span
                :key="i-1"
                class="body-1"
              >{{ item.location[i-1] }}</span>{{ i-1 != totalVisibleLocation(item)-1 ? ',' : '' }}
            </template>
          </div>
        </v-layout>

        <div>
          <v-chip :key="i-1" v-for="i in totalVisibleTags(item)">
            {{ item.job_tags[i-1] }}
          </v-chip>
        </div>

      </v-flex>
    </v-layout>
  </v-card-title>

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
    },
    slim: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    dSlim: false
  }),

  created() {
    this.dSlim = this.slim ? true : false
  },

  computed: {
    totalVisible() {
      return this.dSlim ? 2 : 5
    },
    isLogged() {
      return this.$bus.session.user !== null
    },
    imgSrc() {
      let user = this.item
      if (typeof user !== 'object' || user === null) {
        return null
      }
      if (typeof user.creator_img_src !== 'string' || !user.creator_img_src.length) {
        return {
          isImg: false,
          text: user.creator_fname.charAt(0).toUpperCase()
        }
      } 
      return {
        isImg: true,
        text: user.creator_img_src
      }
    }
  },

  methods: {
    totalVisibleLocation(item) {
      return item.location.length > this.totalVisible
        ? this.totalVisible : item.location.length
    },
    totalVisibleTags(item) {
      return item.job_tags.length > this.totalVisible
        ? this.totalVisible : item.job_tags.length
    },

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
            console.warn(res.data)
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
