<template>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Transactions récentes</h3>
            <div v-if="transactions.length === 0" class="text-center py-10">
                <i class="bi bi-inbox text-5xl text-gray-400"></i>
                <p class="mt-4 text-xl font-semibold text-gray-500">Pas de transaction récente</p>
            </div>
            <table v-else class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th v-for="column in columns" :key="column.key"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{
                            column.label }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="transaction in transactions" :key="transaction.id">
                        <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap">
                            <template v-if="column.key === 'type'">
                                <span :class="getTypeClass(transaction.type, transaction.sub_type)">{{
                                    getTransactionType(transaction.type, transaction.sub_type) }}</span>
                            </template>
                            <template v-else-if="column.key === 'actions'">
                                <button @click="viewTransaction(transaction)"
                                    class="text-indigo-600 hover:text-indigo-800 mr-2">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button @click="editTransaction(transaction)"
                                    class="text-yellow-500 hover:text-yellow-700 mr-2">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button @click="confirmDeleteTransaction(transaction)"
                                    class="text-red-600 hover:text-red-800">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </template>
                            <template v-else>
                                {{ column.format ? column.format(transaction[column.key]) : transaction[column.key] }}
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>


const props = defineProps(['transactions']);
const emit =  defineEmits(['view-transaction', 'edit-transaction', 'delete-transaction']);

const columns = [
    { key: "transaction_date", label: "Date", format: (value) => formatDate(value) },
    { key: "amount", label: "Montant", format: (value) => formatCurrency(value) },
    { key: "type", label: "Type" },
    { key: "actions", label: "Actions" },
];

const formatCurrency = (value) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
};

const getTransactionType = (type, subType) => {
    if (subType) {
        switch (subType.toLowerCase()) {
            case "contract_commission":
                return "commission";
            case "contract_caution":
                return "caution";
        }
    }

    switch (type.toLowerCase()) {
        case "payment":
            return "mensualité";
        case "payout":
            return "versement";
        case "expense":
            return "dépense";
        default:
            return type;
    }
};

const getTypeClass = (type, subType) => {
    if (subType) {
        switch (subType.toLowerCase()) {
            case "contract_commission":
                return "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800";
            case "contract_caution":
                return "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800";
        }
    }

    if (!type) return "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800";

    switch (type.toLowerCase()) {
        case "payment":
            return "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800";
        case "payout":
            return "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800";
        case "expense":
            return "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800";
        default:
            return "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800";
    }
};

const viewTransaction = (transaction) => {
    emit('view-transaction', transaction);
};

const editTransaction = (transaction) => {
    emit('edit-transaction', transaction);
};

const confirmDeleteTransaction = (transaction) => {
    emit('delete-transaction', transaction);
};
</script>

<style scoped>
@import "bootstrap-icons/font/bootstrap-icons.css";
</style>
