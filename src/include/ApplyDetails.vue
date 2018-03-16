<template>
<div>

  <v-layout v-if="item.a_status == 2 && item.a_interview_date">
    <v-flex xs12 sm4>
      <div class="subheader">Interview details</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-card>
        <v-layout>
          <div class="py-2 px-3">
            <v-icon color="primary">event</v-icon>
          </div>
          <div
            class="py-2 subheading"
            v-text="$wrap.date(item.a_interview_date)"
          />
        </v-layout>

        <v-layout>
          <div class="py-2 px-3">
            <v-icon color="primary">access_time</v-icon>
          </div>
          <div
            class="py-2 subheading"
            v-text="item.a_interview_time"
          />
        </v-layout>
      </v-card>
    </v-flex>
  </v-layout>

  <v-layout>
    <v-flex hidden-xs-only sm3>
      <div class="subheader">Applicant</div>
    </v-flex>
    <v-flex xs12 sm9 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <icon-img
            :item="item"
            size="24"
            class="elevation-1"
            fname="applier_fname"
            img="applier_img_src"
            v-if="typeof item.applier_img_src === 'string' && item.applier_img_src.length"
          />
          <v-icon v-else>person</v-icon>
        </div>
        <div class="py-2 subheading">
          <router-link
            slot="activator"
            :to="'/profile/' + item.a_user_id"
            v-text="item.applier_fname + ' ' + item.applier_lname"
          />
        </div>
      </v-layout>
    </v-flex>
  </v-layout>

  <v-layout>
    <v-flex hidden-xs-only sm3>
      <v-subheader>Subject</v-subheader>
    </v-flex>
    <v-flex sm9 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>subject</v-icon>
        </div>
        <div
          class="py-2 subheading"
          v-text="item.a_subject"
        />
      </v-layout>
    </v-flex>
  </v-layout>

  <v-layout>
    <v-flex hidden-xs-only sm3>
      <v-subheader>Applied at</v-subheader>
    </v-flex>
    <v-flex sm9 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>access_time</v-icon>
        </div>
        <div
          class="py-2 subheading"
          v-text="$wrap.datetime(item.a_created_at, true, true)"
        />
      </v-layout>
    </v-flex>
  </v-layout>

  <v-layout>
    <v-flex hidden-xs-only sm3>
      <v-subheader>Message</v-subheader>
    </v-flex>
    <v-flex sm9 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>message</v-icon>
        </div>
        <div
          class="py-2 subheading"
          v-text="item.a_body"
        />
      </v-layout>
    </v-flex>
  </v-layout>

  <v-layout v-if="item.a_files.length">
    <v-flex hidden-xs-only sm3>
      <v-subheader>Attachments</v-subheader>
    </v-flex>
    <v-flex sm9 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>attachment</v-icon>
        </div>
        <v-list
          dense
          class="full-width elevation-1"
        >
        <template v-for="(file, i) in item.a_files">
          <v-list-tile
            :key="'file-' + i"
            ripple
            target="_blank"
            :href="$wrap.localAttach(file)"
          >
            <v-list-tile-content v-text="file"/>
          </v-list-tile>
          <v-divider
            :key="'divider-' + i"
            v-if="item.a_files.length-1 != i"
          />
        </template>
        </v-list>
      </v-layout>
    </v-flex>
  </v-layout>

  <v-layout>
    <v-flex hidden-xs-only sm3>
      <v-subheader>Status</v-subheader>
    </v-flex>
    <v-flex sm9 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <apply-status
            icon
            :item="item"
          />
        </div>
        <div
          class="py-2 subheading"
          v-text="$wrap.applyStatus(item.a_status)"
        />
      </v-layout>
    </v-flex>
  </v-layout>

</div>
</template>

<script>
import IconImg from '@/include/IconImg'
import ApplyStatus from '@/include/ApplyStatus'

export default {
  name: 'apply-details',
  components: {
    IconImg,
    ApplyStatus
  },
  props: {
    item: Object
  }
}
</script>
