<template>
<div>

  <!-- employer -->

  <v-layout>
    <v-flex xs12 sm4>
      <div class="subheader">Employer</div>
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
            :to="'/profile/' + job.created_by"
            v-text="job.creator_fname + ' ' + job.creator_lname"
          />
        </div>
      </v-layout>
    </v-flex>

  </v-layout>

  <!-- title -->

  <v-layout>
    <v-flex xs12 sm4>
      <div class="subheader">Job title</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>work</v-icon>
        </div>
        <div
          class="py-2 subheading"
          v-text="job.title">
        </div>
      </v-layout>
    </v-flex>

  </v-layout>

  <!-- description -->

  <v-layout>

    <v-flex xs12 sm4>
      <div class="subheader">Job description</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>subject</v-icon>
        </div>
        <div
          class="py-2 subheading"
          v-text="job.description"
        ></div>
      </v-layout>
    </v-flex>

  </v-layout>

  <!-- date -->

  <v-layout>

    <v-flex xs12 sm4>
      <div class="subheader">Work date</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>date_range</v-icon>
        </div>
        <div
          class="py-2 subheading"
          v-text="$wrap.date(job.dateFrom) + ' to ' + $wrap.date(job.dateTo)"
        ></div>
      </v-layout>
    </v-flex>

  </v-layout>

  <!-- time -->

  <v-layout>

    <v-flex xs12 sm4>
      <div class="subheader">Work hours</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>access_time</v-icon>
        </div>
        <div
          class="py-2 subheading"
          v-text="$wrap.HMSToHM(job.timeFrom) + ' to ' + $wrap.HMSToHM(job.timeTo)"
        ></div>
      </v-layout>
    </v-flex>

  </v-layout>

  <!-- location -->

  <v-layout>

    <v-flex xs12 sm4>
      <div class="subheader">Location</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>location_on</v-icon>
        </div>
        <div class="py-2 subheading">
          <template v-for="(location, i) in job.location">
            <span
              :key="location"
            >{{ location }}</span>{{ i != job.location.length-1 ? ',' : '' }}
          </template>
        </div>
      </v-layout>
    </v-flex>

  </v-layout>

  <!-- location -->

  <v-layout>

    <v-flex xs12 sm4>
      <div class="subheader">Related job tags</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-layout>
        <div class="">
          <v-chip
            :key="job"
            v-for="job in job.job_tags"
          >{{ job }}</v-chip>
        </div>
      </v-layout>
    </v-flex>

  </v-layout>

</div>
</template>

<script>
export default {
  name: 'job-details',
  props: {
    job: Object
  },
  computed: {
    imgSrc() {
      let user = this.job
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
  }
}
</script>
