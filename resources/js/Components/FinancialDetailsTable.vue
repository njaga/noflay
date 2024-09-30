<template>
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ title }}</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr>
                        <th v-for="column in columns" :key="column"
                            class="py-2 px-4 border-b bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ column }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(row, index) in data" :key="index">
                        <td v-for="(value, key) in row" :key="key"
                            class="py-2 px-4 whitespace-nowrap text-sm text-gray-900">
                            {{ formatValue(key, value) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p v-if="data.length === 0" class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
    </div>
</template>

<script setup>


const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    columns: {
        type: Array,
        required: true
    }
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(value);
};

const formatValue = (key, value) => {
    if (key === 'amount' || key.toLowerCase().includes('montant')) {
        return formatCurrency(value);
    }
    return value;
};
</script>

<style scoped>
.overflow-x-auto {
    overflow-x: auto;
}

table {
    border-collapse: separate;
    border-spacing: 0;
}

th {
    position: sticky;
    top: 0;
    z-index: 1;
    background-color: #f9fafb;
}

th,
td {
    border-right: 1px solid #e5e7eb;
}

th:last-child,
td:last-child {
    border-right: none;
}

tr:last-child td {
    border-bottom: none;
}
</style>
