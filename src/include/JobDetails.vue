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
          <icon-img
            :item="job"
            size="24"
            class="elevation-1"
            fname="creator_fname"
            img="creator_img_src"
            v-if="typeof job.creator_img_src === 'string' && job.creator_img_src.length"
          />
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

  <v-layout v-if="job.description">

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

  <!-- payment -->

  <v-layout>

    <v-flex xs12 sm4>
      <div class="subheader">Payment</div>
    </v-flex>
    <v-flex xs12 class="py-1">
      <v-layout>
        <div class="py-2 px-3">
          <v-icon>payment</v-icon>
        </div>
        <div
          class="py-2 subheading"
          v-text="job.payment"
        ></div>
      </v-layout>
    </v-flex>

  </v-layout>

  <!-- tags -->

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
import IconImg from '@/include/IconImg'

export default {
  name: 'job-details',
  components: {
    IconImg
  },
  props: {
    job: Object
  }
}
</script>
