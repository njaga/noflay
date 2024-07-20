<template>
    <div class="p-6 sm:p-10">
        <div v-if="payouts.length === 0" class="text-center py-10">
            <i class="bi bi-inbox text-5xl text-gray-400"></i>
            <p class="mt-4 text-xl font-semibold text-gray-500">Aucun versement</p>
            <p class="mt-2 text-gray-400">
                Il n'y a pas de versements correspondant à vos critères de recherche.
            </p>
        </div>
        <div v-else-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div
                v-for="payout in payouts"
                :key="payout.id"
                class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden border border-gray-100"
            >
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <span
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-inner"
                            >
                                {{ payout.landlord && payout.landlord.first_name ? payout.landlord.first_name[0] : '' }}{{ payout.landlord && payout.landlord.last_name ? payout.landlord.last_name[0] : '' }}
                            </span>
                            <div class="ml-4">
                                <div class="font-semibold text-gray-800">
                                    {{ payout.landlord ? `${payout.landlord.first_name} ${payout.landlord.last_name}` : 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500 flex items-center mt-1">
                                    <i class="bi bi-calendar3 mr-1"></i>
                                    {{ formatDate(payout.transaction_date) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-bold text-indigo-600">{{ formatCurrency(payout.amount) }}</div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button
                            @click="$emit('viewPayout', payout)"
                            class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                        >
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <button
                            @click="$emit('editPayout', payout)"
                            class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200"
                        >
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button
                            @click="$emit('confirmDelete', payout)"
                            class="text-red-600 hover:text-red-800 transition-colors duration-200"
                        >
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th v-for="header in tableHeaders" :key="header.key" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ header.label }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="payout in payouts" :key="payout.id">
                        <td v-for="header in tableHeaders" :key="header.key" class="px-6 py-4 whitespace-nowrap">
                            <div v-if="header.key === 'landlord'" class="flex items-center">
                                <span
                                    v-if="payout.landlord"
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-inner mr-2"
                                >
                                    {{ payout.landlord && payout.landlord.first_name ? payout.landlord.first_name[0] : '' }}{{ payout.landlord && payout.landlord.last_name ? payout.landlord.last_name[0] : '' }}
                                </span>
                                <div v-if="payout.landlord">
                                    {{ payout.landlord ? `${payout.landlord.first_name} ${payout.landlord.last_name}` : 'N/A' }}
                                </div>
                                <div v-else>
                                    N/A
                                </div>
                            </div>
                            <div v-else-if="header.key === 'amount'" class="font-semibold text-indigo-600">
                                {{ formatCurrency(payout[header.key]) }}
                            </div>
                            <div v-else-if="header.key === 'transaction_date'">
                                {{ formatDate(payout[header.key]) }}
                            </div>
                            <div v-else>
                                {{ payout[header.key] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="$emit('viewPayout', payout)" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <button @click="$emit('editPayout', payout)" class="text-indigo-600 hover:text-indigo-800 mr-2">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button @click="$emit('confirmDelete', payout)" class="text-red-600 hover:text-red-800">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
const props = defineProps(['payouts', 'viewMode', 'tableHeaders']);

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(amount);
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
};
</script>
