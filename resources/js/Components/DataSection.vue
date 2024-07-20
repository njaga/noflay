<template>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">{{ title }}</h3>
        </div>
        <div class="p-6">
            <div v-if="data && data.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th v-for="column in columns" :key="column.key" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ column.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in data" :key="item.id">
                            <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ formatColumnValue(item, column) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="text-center py-4 text-gray-500">
                Aucune donnée disponible
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'DataSection',
    props: {
        title: {
            type: String,
            required: true
        },
        data: {
            type: Array,
            required: true
        },
        columns: {
            type: Array,
            required: true
        }
    },
    mounted() {
        console.log(`DataSection "${this.title}" mounted with ${this.data.length} items`);
        console.log('First item:', this.data[0]);
        console.log('Columns:', this.columns);
    },
    methods: {
        formatColumnValue(item, column) {
            const value = this.getNestedValue(item, column.key);
            console.log(`Formatting value for ${column.key}:`, value);
            return column.format ? column.format(value) : value;
        },
        getNestedValue(obj, key) {
            return key.split('.').reduce((o, k) => (o || {})[k], obj);
        }
    }
});
</script>

<style scoped>
.min-w-full {
    min-width: 100%;
}
</style>
