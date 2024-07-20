<template>
    <div ref="chartContainer"></div>
  </template>

  <script>
  import { defineComponent, onMounted, watch, ref } from 'vue';
  import * as echarts from 'echarts';

  export default defineComponent({
    props: {
      chartData: {
        type: Array,
        required: true
      }
    },
    setup(props) {
      const chartContainer = ref(null);
      let chart = null;

      const initChart = () => {
        if (chartContainer.value) {
          chart = echarts.init(chartContainer.value);
          updateChart();
        }
      };

      const updateChart = () => {
        if (chart) {
          const option = {
            tooltip: {
              trigger: 'axis'
            },
            legend: {
              data: ['Paiements', 'Dépenses']
            },
            xAxis: {
              type: 'category',
              data: props.chartData.map(item => item.name)
            },
            yAxis: {
              type: 'value'
            },
            series: [
              {
                name: 'Paiements',
                type: 'line',
                data: props.chartData.map(item => item.Paiements)
              },
              {
                name: 'Dépenses',
                type: 'line',
                data: props.chartData.map(item => item.Dépenses)
              }
            ]
          };
          chart.setOption(option);
        }
      };

      onMounted(() => {
        initChart();
      });

      watch(() => props.chartData, () => {
        updateChart();
      }, { deep: true });

      return {
        chartContainer
      };
    }
  });
  </script>
