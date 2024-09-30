<template>
    <BaseChart type="line" :data="chartData" :options="mergedOptions" />
</template>

<script setup>
import { computed } from "vue";
import BaseChart from "@/Components/BaseChart.vue";

const props = defineProps({
    chartData: {
        type: Object,
        required: true,
    },
    chartOptions: {
        type: Object,
        default: () => ({}),
    },
});

const mergedOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    ...props.chartOptions,
}));
</script>
