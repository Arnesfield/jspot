<template>
<v-card>
  <v-card-title primary-title>
    <div>
      <h3 class="title mb-2" v-html="title"></h3>
      <div
        class="caption grey--text"
      >
        <template v-if="!small">Rating stats of user</template>
        <template v-else>
          <v-icon
            :style="{ color: getColor(getTotal(0, 1)) }"
            style="vertical-align: -2px"
            size="14px"
          >star</v-icon>
          <template>{{ getTotal() }}</template>&nbsp;
          <v-icon
            style="vertical-align: -2px"
            size="14px"
          >person</v-icon>
          <template>{{ getTotalPeople() }}</template>
        </template>
      </div>
    </div>
  </v-card-title>
  <v-card-text
    :class="{
      'py-0': small,
      'pb-3 pt-1': !small,
    }"
  >
    <div
      v-if="loading && !ratings"
      class="text-xs-center caption grey--text pa-2"
    >Loading...</div>

    <v-layout row wrap>
      <v-flex
        xs12
        sm3
        md3
        lg2
        v-if="!loading && ratings && !small"
      >
        <div class="text-xs-center">
          <div
            class="display-3 grey--text text--darken-1"
            style="font-weight: 100"
            v-text="getTotal()"
          />
          <simple-star-rating
            :value="getTotal(0, 1)"
            size="18px"
          />
          <div class="mt-2 caption grey--text">
            <v-icon size="14px" style="vertical-align: -2px">person</v-icon>
            <span>{{ getTotalPeople() }} total</span>
          </div>
        </div>
      </v-flex>
      <v-flex
        xs12
        sm9
        md9 
        v-bind="{
          lg10: !small,
          lg12: small
        }"
      >
        <canvas
          ref="chart"
          :width="width"
          :height="height"
        ></canvas>
      </v-flex>
    </v-layout>
  </v-card-text>
  <slot name="actions"/>
</v-card>
</template>

<script>
import qs from 'qs'
import Chart from 'chart.js'
import SimpleStarRating from '@/include/SimpleStarRating'

export default {
  name: 'ratings-graph',
  components: {
    SimpleStarRating
  },
  props: {
    id: [Number, String],
    title: {
      type: String,
      default: 'Rating Statistics'
    },
    small: {
      type: Boolean,
      default: false
    },
    width: {
      type: [Number, String],
      default: 360
    },
    height: {
      type: [Number, String],
      default: 128
    }
  },
  data: () => ({
    url: '/reviews/statistics',
    chart: null,
    ratings: null,
    loading: false
  }),
  watch: {
    id(e) {
      this.fetch()
    },
    ratings: {
      deep: true,
      handler(e) {
        this.setChart()
      }
    }
  },
  created() {
    this.$bus.$on('update--ratings-graph', this.fetch)
  },
  mounted() {
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('update--ratings-graph', this.fetch)
  },
  methods: {
    getColor(e) {
      switch (Number(e)) {
        case 1: return '#ff6f31'
        case 2: return '#ff9f02'
        case 3: return '#ffcf02'
        case 4: return '#99cc00'
        case 5: return '#88b131'
      }
    },
    getTotalPeople() {
      if (!this.ratings) {
        return 0
      }
      return Object.values(this.ratings).reduce((total, e) => total += Number(e), 0)
    },
    getTotalRatings() {
      if (!this.ratings) {
        return 0
      }
      return Object.keys(this.ratings).reduce((total, key) => {
        return total += Number(this.ratings[key]) * Number(key)
      }, 0)
    },
    getTotal(n, m) {
      n = n || 1
      m = m || 10
      let totalPeople = this.getTotalPeople()
      if (!this.ratings || totalPeople == 0) {
        return (0).toFixed(n)
      }
      return (Math.floor(this.getTotalRatings() / (totalPeople * 5) * 5 * m) / m).toFixed(n)
    },
    setChart() {
      if (!this.$refs.chart || this.ratings === null) {
        return
      }
      // destroy current chart
      if (this.chart !== null) {
        this.chart.destroy()
      }

      let keys = Object.keys(this.ratings).reverse()
      let values = Object.values(this.ratings).reverse()

      let datasets = [{
        label: 'Rating',
        backgroundColor: ['#88b131', '#99cc00', '#ffcf02', '#ff9f02', '#ff6f31'],
        borderColor: ['#88b131', '#99cc00', '#ffcf02', '#ff9f02', '#ff6f31'],
        data: values,
        fill: true
      }]

      this.chart = new Chart(this.$refs.chart, {
        type: 'horizontalBar',
        data: {
          labels: keys,
          datasets: datasets
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            display: false
          },
          tooltips: {
            mode: 'index',
            intersect: false
          },
          hover: {
            mode: 'nearest',
            intersect: true
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: false
              },
              display: false,
              ticks: {
                suggestedMin: 0,
                suggestedMax: 1,
                beginAtZero: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: false
              },
            	stacked: true
            }]
          }
        }
      })
    },

    fetch() {
      this.loading = true
      this.$http.post(this.url, qs.stringify({
        id: this.id
      })).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.ratings = res.data.ratings
        // this.setChart()
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
