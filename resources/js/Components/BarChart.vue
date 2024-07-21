<template>
    <div class="chart-container" style="position: relative; height:300px; width:100%">
      <Bar :data="chartData" :options="chartOptions" />
    </div>
  </template>

  <script setup>
  import { Bar } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

  const props = defineProps({
    chartData: {
      type: Object,
      required: true
    }
  })

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false
  }
  </script>
