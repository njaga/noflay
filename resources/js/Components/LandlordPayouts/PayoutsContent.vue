<template>
    <div class="p-6 sm:p-10 bg-gray-50">
        <div
            v-if="payouts.length === 0"
            class="text-center py-10 bg-white rounded-lg shadow-sm"
        >
            <i class="bi bi-inbox text-6xl text-gray-300"></i>
            <h3 class="mt-4 text-xl font-semibold text-gray-700">
                Aucun versement
            </h3>
            <p class="mt-2 text-gray-500">
                Il n'y a pas de versements correspondant à vos critères de
                recherche.
            </p>
        </div>
        <div
            v-else-if="viewMode === 'grid'"
            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"
        >
            <div
                v-for="payout in payouts"
                :key="payout.id"
                class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200"
            >
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <span
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-inner"
                            >
                                {{
                                    payout.landlord &&
                                    payout.landlord.first_name
                                        ? payout.landlord.first_name[0]
                                        : ""
                                }}{{
                                    payout.landlord && payout.landlord.last_name
                                        ? payout.landlord.last_name[0]
                                        : ""
                                }}
                            </span>
                            <div class="ml-4">
                                <div class="font-semibold text-gray-800">
                                    {{
                                        payout.landlord
                                            ? `${payout.landlord.first_name} ${payout.landlord.last_name}`
                                            : "N/A"
                                    }}
                                </div>
                                <div
                                    class="text-sm text-gray-500 flex items-center mt-1"
                                >
                                    <i class="bi bi-calendar3 mr-1"></i>
                                    {{ formatDate(payout.transaction_date) }}
                                </div>
                            </div>
                        </div>
                        <div
                            :class="[
                                'px-3 py-1 rounded-full text-xs font-medium',
                                statusClass(payout.status),
                            ]"
                        >
                            {{ payout.status }}
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-bold text-indigo-600">
                            {{ formatCurrency(payout.amount) }}
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="bi bi-credit-card mr-2 text-gray-500"></i>
                            <span class="text-sm text-gray-600">{{
                                payout.payment_method
                            }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-2">
                        <button
                            @click="$emit('view', payout)"
                            class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                        >
                            <i class="bi bi-eye mr-1"></i>
                            Voir
                        </button>
                        <button
                            @click="$emit('edit', payout)"
                            class="text-green-600 hover:text-green-800 transition-colors duration-200"
                        >
                            <i class="bi bi-pencil mr-1"></i>
                            Modifier
                        </button>
                        <button
                            @click="$emit('delete', payout)"
                            class="text-red-600 hover:text-red-800 transition-colors duration-200"
                        >
                            <i class="bi bi-trash mr-1"></i>
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-else-if="viewMode === 'table'"
            class="overflow-x-auto bg-white rounded-xl shadow-sm"
        >
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            v-for="header in tableHeaders"
                            :key="header.key"
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ header.label }}
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="payout in payouts"
                        :key="payout.id"
                        class="hover:bg-gray-50 transition-colors duration-200"
                    >
                        <td
                            v-for="header in tableHeaders"
                            :key="header.key"
                            class="px-6 py-4 whitespace-nowrap"
                        >
                            <div
                                v-if="header.key === 'landlord'"
                                class="flex items-center"
                            >
                                <div class="flex-shrink-0 h-10 w-10">
                                    <span
                                        class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg"
                                    >
                                        {{
                                            payout.landlord &&
                                            payout.landlord.first_name
                                                ? payout.landlord.first_name[0]
                                                : ""
                                        }}{{
                                            payout.landlord &&
                                            payout.landlord.last_name
                                                ? payout.landlord.last_name[0]
                                                : ""
                                        }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            payout.landlord
                                                ? `${payout.landlord.first_name} ${payout.landlord.last_name}`
                                                : "N/A"
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else-if="header.key === 'amount'"
                                class="text-sm font-semibold text-gray-900"
                            >
                                {{ formatCurrency(payout.amount) }}
                            </div>
                            <div
                                v-else-if="header.key === 'transaction_date'"
                                class="text-sm text-gray-900"
                            >
                                {{ formatDate(payout.transaction_date) }}
                            </div>
                            <div
                                v-else-if="header.key === 'payment_method'"
                                class="text-sm text-gray-900"
                            >
                                {{ payout.payment_method }}
                            </div>
                            <div
                                v-else-if="header.key === 'status'"
                                :class="[
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    statusClass(payout.status),
                                ]"
                            >
                                {{ payout.status }}
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                        >
                            <button
                                @click="$emit('view', payout)"
                                class="text-blue-600 hover:text-blue-900 mr-2 transition-colors duration-200"
                            >
                                Voir
                            </button>
                            <button
                                @click="$emit('edit', payout)"
                                class="text-green-600 hover:text-green-900 mr-2 transition-colors duration-200"
                            >
                                Modifier
                            </button>
                            <button
                                @click="$emit('delete', payout)"
                                class="text-red-600 hover:text-red-900 transition-colors duration-200"
                            >
                                Supprimer
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    payouts: {
        type: Array,
        required: true,
    },
    viewMode: {
        type: String,
        required: true,
    },
    tableHeaders: {
        type: Array,
        required: true,
    },
});

const emit =  defineEmits(["view", "edit", "delete"]);

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "EUR",
    }).format(amount);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const statusClass = (status) => {
    switch (status) {
        case "Payé":
            return "bg-green-100 text-green-800";
        case "En attente":
            return "bg-yellow-100 text-yellow-800";
        case "Annulé":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};
</script>

<style scoped>
.grid > div,
.overflow-x-auto {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
