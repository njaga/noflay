<template>
    <div class="flex space-x-4">
        <button @click="$emit('generateContractPDF')" class="btn-primary">
            <i class="fas fa-download mr-2"></i>
            Télécharger le contrat
        </button>
        <button @click="$emit('generateInvoicePDF')" class="btn-primary">
            <i class="fas fa-download mr-2"></i>
            Télécharger la facture
        </button>
    </div>
</template>

<script setup>
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
}
</style>
