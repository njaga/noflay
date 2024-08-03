<script setup>
import { Scatter } from 'vue-chartjs'
import { Chart as ChartJS, LinearScale, PointElement, LineElement, Tooltip, Legend } from 'chart.js'

ChartJS.register(LinearScale, PointElement, LineElement, Tooltip, Legend)

const props = defineProps({
  chartData: {
    type: Object,
    required: true
  },
  options: {
    type: Object,
    default: () => ({
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true
        }
      },
      scales: {
        x: {
          type: 'linear',
          position: 'bottom'
        },
        y: {
          beginAtZero: true
        }
      }
    })
  }
})
</script>

<template>
  <Scatter :data="chartData" :options="options" />
</template>
