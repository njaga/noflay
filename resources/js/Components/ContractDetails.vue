<template>
    <div class="bg-gradient-to-r from-indigo-100 to-blue-100 p-6 rounded-lg mb-8 shadow-sm">
        <h2 class="text-xl font-semibold text-indigo-800 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> Détails du Contrat
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <ContractDetailItem icon="user" label="Locataire" :value="tenantName" />
            <ContractDetailItem icon="building" label="Propriété" :value="contract.property.name" />
            <ContractDetailItem icon="calendar-alt" label="Date de début" :value="contract.start_date" />
            <ContractDetailItem icon="calendar-alt" label="Date de fin" :value="endDate" />
            <ContractDetailItem icon="file-alt" label="Type de contrat" :value="contract.contract_type" />
            <ContractDetailItem v-if="contract.contract_type === 'commercial'" icon="briefcase" label="Nom de l'entreprise" :value="contract.company" />
            <ContractDetailItem v-if="contract.contract_type === 'commercial'" icon="user-tie" label="Représentant de l'entreprise" :value="contract.representative" />
            <ContractDetailItem v-if="contract.contract_type === 'commercial'" icon="book" label="Registre de commerce" :value="contract.trade_register" />
            <ContractDetailItem icon="money-bill-wave" label="Loyer mensuel" :value="formatCurrency(monthlyRent)" />
            <ContractDetailItem icon="percent" label="TVA (18%)" :value="formatCurrency(calculatedTVA)" />
            <ContractDetailItem icon="trash-alt" label="TOM" :value="formatCurrency(calculatedTOM)" />
            <ContractDetailItem icon="hand-holding-usd" label="Total à payer" :value="formatCurrency(totalRent)" />
        </div>
    </div>
</template>

<script setup>
import { formatCurrency } from '@/utils'; // Assuming you have a utility function to format currency

const props = defineProps({
    contract: Object,
    tenantName: String,
    endDate: String,
    monthlyRent: Number,
    calculatedTVA: Number,
    calculatedTOM: Number,
    totalRent: Number
});
</script>

<style scoped>
</style>
