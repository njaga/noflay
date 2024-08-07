<template>
    <div class="relative" style="height: 300px;">
        <canvas ref="chartCanvas"></canvas>
        <div v-if="!hasData" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-100 bg-opacity-75">
            <BarChart2 class="text-gray-400 mb-2" :size="48" />
            <p class="text-gray-500 text-lg font-medium">Aucune donnée disponible</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Chart from 'chart.js/auto';
import { BarChart2 } from 'lucide-vue-next';

const props = defineProps({
    type: {
        type: String,
        required: true
    },
    data: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        default: () => ({})
    }
});

const chartCanvas = ref(null);
let chart = null;

const hasData = computed(() => {
    return props.data.datasets && props.data.datasets.some(dataset => dataset.data && dataset.data.length > 0);
});

const createChart = () => {
    if (chart) {
        chart.destroy();
    }

    const ctx = chartCanvas.value.getContext('2d');
    chart = new Chart(ctx, {
        type: props.type,
        data: props.data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            ...props.options,
            plugins: {
                ...props.options.plugins,
                legend: {
                    display: hasData.value,
                    ...props.options.plugins?.legend
                }
            }
        }
    });
};

onMounted(() => {
    if (hasData.value) {
        createChart();
    }
});

watch(() => props.data, (newData) => {
    if (hasData.value) {
        if (chart) {
            chart.data = newData;
            chart.update();
        } else {
            createChart();
        }
    } else if (chart) {
        chart.destroy();
        chart = null;
    }
}, { deep: true });

watch(() => props.options, (newOptions) => {
    if (chart) {
        chart.options = {
            ...chart.options,
            ...newOptions,
            plugins: {
                ...chart.options.plugins,
                ...newOptions.plugins,
                legend: {
                    display: hasData.value,
                    ...newOptions.plugins?.legend
                }
            }
        };
        chart.update();
    }
}, { deep: true });

watch(() => props.type, (newType) => {
    if (chart) {
        chart.destroy();
    }
    if (hasData.value) {
        createChart();
    }
});
</script>
