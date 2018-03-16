<template>
<div class="full-height" v-scroll="onScroll" id="top">
  <v-toolbar
    dark
    fixed
    class="full-width"
    :class="{
      [toolbarElevation]: true
    }"
    :color="toolbarColor"
  >
    <v-toolbar-title
      @click="toolbarClick"
      class="clickable pt-3x"
    >
      <v-avatar size="32px" style="background-color: #286cb3">
        <img src="/static/images/logo.png"/>
      </v-avatar>
      <span>JSpot</span>
    </v-toolbar-title>

    <v-spacer/>
    <v-toolbar-items>
      <v-btn
        flat
        class="hidden-sm-and-down"
        @click="$vuetify.goTo('#about', options)"
      >About</v-btn>
      <v-btn flat to="/signup">Sign up</v-btn>
      <v-btn flat to="/login">
        <span class="mr-2">Login</span>
        <v-icon>exit_to_app</v-icon>
      </v-btn>
    </v-toolbar-items>
  </v-toolbar>

  <v-jumbotron
    height="640"
    :gradient="gradient"
    class="pa-0 white"
  >
    <v-parallax
      class="pa-0"
      height="640"
      src="./static/images/job.png"
    >
        <!-- style="background-color: rgba(0, 87, 173, 0.6)" -->
      <div class="full-height dim">
          <!-- class="full-height" -->
        <v-slide-y-reverse-transition>
          <v-container
            fill-height
            v-show="animTitle"
          >
            <v-layout
              row
              wrap
              align-center
            >
              <div>
                <h3 class="display-3">
                  <template>Find jobs with</template>
                  <strong class="mono">JSpot</strong>
                  <v-icon dark style="font-size: inherit">search</v-icon>
                </h3>
                <h6 class="title">Lorem ipsum dolor sit amet.</h6>
                <v-divider class="my-3" dark/>
                <searchbar
                  solo
                  class="mx-0"
                  :solo-inverted="false"
                />
              </div>
            </v-layout>
          </v-container>
        </v-slide-y-reverse-transition>
      </div>
    </v-parallax>
  </v-jumbotron>

  <div id="about" class="elevation-6">
    <v-jumbotron
      dark
      height="640"
      class="pa-0"
    >
      <v-parallax
        class="pa-0 about-bg smooth-opacity white"
        height="640"
        :style="{ opacity: aboutOpacity }"
      >
        <div class="dim-04 full-height">
          <v-container class="full-height">
            <v-layout
              align-center
              class="full-height"
            >
              <v-flex hidden-xs-only sm4 lg6>

              </v-flex>
              <v-flex xs12 sm8 lg6>
                <div class="text-xs-center text-sm-right full-width">
                  <h2 class="display-3 pl-3 grey--text text--darken-1">About Us</h2>
                  <p
                    class="headline grey--text"
                  >JSpot helps you to find the jobs that you are looking for. Lorem ipsum dolor sit amet.</p>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </div>
      </v-parallax>
    </v-jumbotron>
  </div>

  <div
    id="numeric"
    style=""
    class="pa-0"
  >
    <!-- height="640" -->
    <!--  cubic-bezier(0.25, 0.8, 0.25, 1) -->
    <v-jumbotron
      style="height: auto"
      class="full-width smooth-opacity"
      :style="{ opacity: numberGradientOpacity }"
      :gradient="numberGradient"
    >
      <v-layout
        justify-center
        align-center
        class="px-0 pb-0 pt-5"
      >
        <div class="hidden-xs-only">
          <h3
            class="display-1 grey--text text--darken-2 hidden-md-and-down"
          >Number of <span class="primary--text">Employers</span></h3>
          <h6 class="headline grey--text text--darken-2">
            <v-icon
              style="font-size: inherit; vertical-align: -4px"
              color="grey darken-2"
            >person</v-icon>
            <template>{{ employers }}</template>
          </h6>
        </div>
        <users-graph
          @input="setUsers"
          class="pa-5"
        />
        <div class="text-xs-right hidden-xs-only">
          <h3
            class="display-1 grey--text text--darken-2 hidden-md-and-down"
          >Number of <span class="warning--text">Employees</span></h3>
          <h6 class="headline grey--text text--darken-2">
            <v-icon
              style="font-size: inherit; vertical-align: -4px"
              color="grey darken-2"
            >person</v-icon>
            <template>{{ employees }}</template>
          </h6>
        </div>
      </v-layout>
    </v-jumbotron>
  </div>

  <div>
    <v-jumbotron
      height="640"
      :gradient="gradient"
      class="pa-0 white"
    >
      <v-parallax
        class="pa-0 users-bg smooth-opacity"
        height="640"
        :style="{ opacity: totalUsersOpacity }"
      >
        <v-container class="full-height">
          <v-layout align-center class="full-height">
            <h2 class="display-4 pr-3">{{ employers + employees }}</h2>
            <h2 class="display-2 pl-3">Total Users</h2>
          </v-layout>
        </v-container>
      </v-parallax>
    </v-jumbotron>
  </div>

  <div>
    <v-container
      grid-list-md
      class="py-5 smooth-opacity"
      :style="{ opacity: reviewsOpacity }"
    >
      <h2
        class="display-3 text-xs-center grey--text text--darken-1 pt-5 pb-2"
        v-text="'Reviews and Ratings'"
      />
      <v-layout justify-center class="mb-4">
        <simple-star-rating
          value="5" 
          size="32px"
        />
      </v-layout>
      <home-reviews class="pb-5"/>
    </v-container>
  </div>

  <div>
    <v-jumbotron
      height="640"
      class="pa-0 white"
    >
      <v-parallax
        class="pa-0 download-bg smooth-opacity"
        height="640"
        :style="{ opacity: downloadOpacity }"
      >
        <v-container class="full-height">
          <v-layout align-center class="full-height">
            <div>
              <h2 class="display-2 primary--text text--darken-1 mb-2">Download the App</h2>
              <h6 class="title primary--text text--lighten-1 mb-3">Find jobs near you on the go</h6>
              <div>
                <img class="clickable img-download" src="/static/images/getplaystore.png"/>
                <img class="clickable img-download" src="/static/images/getappstore.png"/>
              </div>
            </div>
          </v-layout>
        </v-container>
      </v-parallax>
    </v-jumbotron>
  </div>

  <div class="primary py-5 white--text">
    <v-container class="py-3">
      <v-layout justify-center align-center class="mb-3">
        <h3 class="display-1 text-xs-center">Start right now and find the job for you.</h3>
      </v-layout>
      <v-layout
        row
        wrap
        align-center
        justify-center
      >
        <v-btn
          dark
          large
          to="/dashboard"
          color="warning"
        >Go to Dashboard</v-btn>
        <v-btn
          dark
          large
          to="/signup"
          color="green"
        >Create my Account</v-btn>
      </v-layout>
    </v-container>
  </div>

  <v-footer
    dark
    :height="null"
    color="primary darken-3"
  >
    <v-container class="mono">
      <v-layout
        row
        wrap
        class="primary--text text--lighten-4 caption"
        align-center
      >
        <v-flex
          justify-center
          align-center
        >&copy; {{ (new Date()).getFullYear() }} JSpot Web and Mobile Application.</v-flex>
        <v-flex
          justify-center
          align-center
          text-sm-right
        >Bukang Liwayway Corporation</v-flex>
      </v-layout>
    </v-container>
  </v-footer>

</div>
</template>

<script>
import Chart from 'chart.js'
import Searchbar from '@/include/Searchbar'
import UsersGraph from '@/include/UsersGraph'
import HomeReviews from '@/include/HomeReviews'
import SimpleStarRating from '@/include/SimpleStarRating'

export default {
  name: 'home',
  components: {
    Searchbar,
    UsersGraph,
    HomeReviews,
    SimpleStarRating
  },
  data: () => ({
    gradient: 'to top right, rgba(30, 136, 229, .7), rgba(0, 64, 146, .7)',
    options: {
      duration: 800,
      offset: -64,
      easing: 'easeInOutCubic'
    },
    scrollOffset: 0,
    toolbarShift: 500,
    numberGradientShift: 980,
    totalUsersShift: 1620,
    aboutShift: 240,
    downloadShift: 2920,
    reviewsShift: 2320,
    animTitle: false,

    employers: 0,
    employees: 0,
    noOfJobs: 0
  }),
  computed: {
    reviewsOpacity() {
      return this.scrollOffset > this.reviewsShift ? 1 : 0
    },
    totalUsersOpacity() {
      return this.scrollOffset > this.totalUsersShift ? 1 : 0
    },
    downloadOpacity() {
      return this.scrollOffset > this.downloadShift ? 1 : 0
    },
    aboutOpacity() {
      return this.scrollOffset > this.aboutShift ? 1 : 0
    },
    numberGradient() {
      return this.scrollOffset > this.numberGradientShift
        ? 'to top right, rgba(230, 230, 230, .8), rgba(255, 255, 255, .8)'
        : ''
    },
    numberGradientOpacity() {
      return this.scrollOffset > this.numberGradientShift ? 1 : 0
    },
    toolbarColor() {
      return this.scrollOffset < this.toolbarShift ? 'transparent' : 'primary'
    },
    toolbarElevation() {
      return this.scrollOffset < this.toolbarShift ? 'elevation-0' : ''
    },
    animNumber() {
      return this.scrollOffset > 420
    }
  },
  mounted() {
    setTimeout(() => {
      this.animTitle = true
    }, 500)
  },
  created() {
    this.fetch()
    this.onScroll()
  },

  methods: {
    toolbarClick() {
      this.$vuetify.goTo('#top', {
        duration: 800,
        offset: 0,
        easing: 'easeInOutCubic'
      })
    },
    onScroll(e) {
      this.scrollOffset = window.pageYOffset || document.documentElement.scrollTop
    },
    fetch() {
      
    },
    setUsers(e) {
      this.employers = Number(e.employers)
      this.employees = Number(e.employees)
    }
  }
}
</script>

<style scoped>
.users-bg {
  background-image: url('/static/images/users.png');
  background-size: 50%;
  background-position: 75% 50%;
}

.about-bg {
  background-image: url('/static/images/about.png');
  background-size: 25%;
  background-position: 18% 50%;
}

.download-bg {
  background-image: url('/static/images/download.png');
  background-size: 18%;
  background-position: 75% 50%;
}

.img-download {
  width: 128px;
}
</style>
