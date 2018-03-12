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
    views: null,
    loading: false
  }),
  watch: {
    id(e) {
      this.fetch()
    },
    views: {
      deep: true,
      handler(e) {
        if (e === null) {
          return
        }
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
      let dates = Object.keys(this.views)
      let views = Object.values(this.views)
      // return;
      new Chart(this.$refs.chart, {
        type: 'line',
        data: {
          labels: dates,
          datasets: [{
            label: null,
            // backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            backgroundColor: '#1e88e5',
            borderColor: '#1e88e5',
            data: views,
            fill: false
          }]
        },
        options: {
          responsive: true,
          legend: {
            display: false
          },
          tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function(tooltipItem) {
                return tooltipItem.yLabel;
              }
            }
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
          ? false
          : this.id
      this.$http.post(this.url, qs.stringify({
        id: id
      })).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.views = res.data.views
        this.setChart()
        this.loading = false
      }).catch(e => {
        console.warn(e)
        this.loading = false
      })
    }
  }
}
</script>
