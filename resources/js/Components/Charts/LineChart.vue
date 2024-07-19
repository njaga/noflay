<template>
    <canvas ref="chartRef"></canvas>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted, toRaw } from 'vue';
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
        type: 'line',
        data: toRaw(props.chartData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            ...toRaw(props.options)
        }
    });
};

onMounted(() => {
    createChart();
});

watch(() => props.chartData, (newData) => {
    if (chart) {
        chart.data = toRaw(newData);
        chart.update('none');  // Use 'none' to prevent animation
    }
}, { deep: true });

onUnmounted(() => {
    if (chart) {
        chart.destroy();
    }
});
</script>
