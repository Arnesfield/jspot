<template>
<v-card>
  <v-card-title primary-title>
    <div>
    <h3 class="title mb-2" v-html="title"></h3>
    <div
      class="caption grey--text"
    >
      <template>Showing views from</template>
      <template>{{ getStartDate() }}</template>
      <template>to</template>
      <template>{{ getEndDate() }}</template>
    </div>
    </div>
  </v-card-title>
  <v-card-text class="pt-1">
    <div
      v-if="loading && !views"
      class="text-xs-center caption grey--text pa-2"
    >Loading...</div>
    <div class="line-graph-container">
      <canvas
        ref="chart"
        width="320"
        height="48"
      ></canvas>
    </div>
  </v-card-text>
</v-card>
</template>

<script>
import qs from 'qs'
import Chart from 'chart.js'
import dynamicColors from '@/assets/js/dynamicColors'

export default {
  name: 'views-graph',
  props: {
    id: Number,
    title: {
      type: String,
      default: 'Views Statistics'
    }
  },
  data: () => ({
    url: '/logs/views',
    chart: null,
    views: null,
    jobs: null,
    titles: null,
    loading: false
  }),
  watch: {
    id(e) {
      this.fetch()
    },
    views: {
      deep: true,
      handler(e) {
        this.setChart()
      }
    }
  },
  created() {
    this.$bus.$on('update--boosts', this.fetch)
  },
  mounted() {
    this.fetch()
  },
  beforeDestroy() {
    this.$bus.$off('update--boosts', this.fetch)
  },
  methods: {
    getStartDate() {
      if (!this.views) {
        return '?'
      }
      return Object.keys(this.views)[0]
    },
    getEndDate() {
      if (!this.views) {
        return '?'
      }
      let keys = Object.keys(this.views)
      return keys[keys.length-1]
    },
    setChart() {
      if (!this.$refs.chart || this.views === null) {
        return
      }
      // destroy current chart
      if (this.chart !== null) {
        this.chart.destroy()
      }

      let dates = Object.keys(this.views)
      let views = Object.values(this.views)

      const primaryColor = 'rgb(30, 136, 229)'

      let colors = [primaryColor]

      let datasets = [{
        label: 'Profile',
        backgroundColor: primaryColor,
        borderColor: primaryColor,
        data: views,
        fill: false
      }]

      // loop through keys of jobs or titles
      let jobKeys = Object.keys(this.jobs)
      jobKeys.forEach(key => {
        let label = this.titles[key]
        let jobDates = Object.keys(this.jobs[key])
        let jobViews = Object.values(this.jobs[key])
        // generate color
        let color = null
        do {
          // if color exists, do dynamic again
          color = dynamicColors()
        } while (colors.indexOf(color) > -1)

        datasets.push({
          label: label,
          backgroundColor: color,
          borderColor: color,
          data: jobViews,
          fill: false
        })
      })
      
      // create chart
      this.chart = new Chart(this.$refs.chart, {
        type: 'line',
        data: {
          labels: dates,
          datasets: datasets
        },
        options: {
          responsive: true,
          // legend: {
          //   display: false
          // },
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
              display: false
            }],
            yAxes: [{
              ticks: {
                suggestedMin: 0,
                suggestedMax: 10,
                beginAtZero: true
              }
            }]
          }
        }
      })
    },
    fetch() {
      this.loading = true
      let id =
        typeof this.id === 'undefined' || this.id === null
          ? null
          : this.id
      this.$http.post(this.url, qs.stringify({
        id: id
      })).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.views = res.data.views
        this.jobs = res.data.jobs
        this.titles = res.data.titles
        this.setChart()
      }).catch(e => {
        console.warn(e)
        this.loading = false
      })
    }
  }
}
</script>
