<template>
<div>
  <div
    v-if="loading && chart === null"
    class="text-xs-center caption grey--text pa-2"
  >Loading...</div>
  <div :style="{width: [width + 'px']}">
    <canvas
      ref="chart"
      :width="width"
      :height="height"
    ></canvas>
  </div>
</div>
</template>

<script>
import Chart from 'chart.js'

export default {
  name: 'users-graph',
  props: {
    width: {
      type: [Number, String],
      default: 280
    },
    height: {
      type: [Number, String],
      default: 640
    }
  },
  data: () => ({
    url: '/home/getUsersCount',
    users: {
      employers: 0,
      employees: 0
    },
    chart: null,
    loading: false
  }),
  watch: {
    users: {
      deep: true,
      handler(e) {
        this.$emit('input', e)
        this.setChart()
      }
    }
  },
  created() {
    this.fetch()
  },

  methods: {
    setChart() {
      if (!this.$refs.chart) {
        return
      }
      // destroy current chart
      if (this.chart !== null) {
        this.chart.destroy()
      }

      let employers = this.users.employers
      let employees = this.users.employees

      this.chart = new Chart(this.$refs.chart, {
        type: 'bar',
        data: {
          labels: ['Employers: ' + employers, 'Employees: ' + employees],
          datasets: [{
            // label: 'Number',
            backgroundColor: ['#1e88e5', '#ff7043'],
            borderColor: ['#1e88e5', '#ff7043'],
            data: [employers, employees],
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            display: false
          },
          tooltips: {
            enabled: !false
            // mode: 'index',
            // intersect: false
          },
          scales: {
            xAxes: [{
              barThickness : 72,
              display: true,
              gridLines: {
                display: true
              },
              ticks: {
                display: true,
                suggestedMin: 0,
                suggestedMax: 1,
                beginAtZero: true
              }
            }],
            yAxes: [{
              display: true,
              gridLines: {
                display: true
              },
            	stacked: true,
              ticks: {
                display: false
              }
            }]
          }
        }
      })
    },
    fetch() {
      this.loading = true
      this.$http.post(this.url).then(res => {
        console.warn(res.data)
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.loading = false
        this.users.employers = res.data.employers
        this.users.employees = res.data.employees
        this.setChart()
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>
