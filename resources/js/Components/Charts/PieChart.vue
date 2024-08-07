<template>
    <BaseChart type="pie" :data="chartData" :options="mergedOptions" />
</template>

<script setup>
import { computed } from "vue";
import BaseChart from "@/Components/BaseChart.vue";

const props = defineProps({
    chartData: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        default: () => ({}),
    },
});

const mergedOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    ...props.options,
}));

// Vérification des données vides
const hasData = computed(() => {
    return (
        props.chartData.datasets &&
        props.chartData.datasets.length > 0 &&
        props.chartData.datasets[0].data &&
        props.chartData.datasets[0].data.length > 0
    );
});

// Si les données sont vides, on retourne un objet vide pour déclencher le message "Aucune donnée disponible"
const safeChartData = computed(() => {
    return hasData.value ? props.chartData : { labels: [], datasets: [] };
});
</script>
