<template>
<div>

  <v-layout>
    <v-flex xs12 sm4>
      <div class="subheader">Applicant</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-avatar
            size="24"
            v-if="imgSrc && imgSrc.isImg"
            class="elevation-1"
          >
            <img :src="$wrap.localImg(imgSrc.text)">
          </v-avatar>
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
import ApplyStatus from '@/include/ApplyStatus'

export default {
  name: 'apply-details',
  components: {
    ApplyStatus
  },
  props: {
    item: Object
  },
  computed: {
    imgSrc() {
      let user = this.item
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
