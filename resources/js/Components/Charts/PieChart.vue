<template>
    <canvas ref="chartRef"></canvas>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    chartData: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        default: () => ({})
    }
});

const chartRef = ref(null);
let chart = null;

const createChart = () => {
    const ctx = chartRef.value.getContext('2d');
    chart = new Chart(ctx, {
        type: 'pie',
        data: props.chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            ...props.options
        }
    });
};

onMounted(() => {
    createChart();
});

watch(() => props.chartData, (newData) => {
    if (chart) {
        chart.data = newData;
        chart.update();
    }
}, { deep: true });

onUnmounted(() => {
    if (chart) {
        chart.destroy();
    }
});
</script>
