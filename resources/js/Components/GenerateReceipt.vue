<template>
    <div>
        <button @click="generateReceipt" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
            <i class="bi bi-file-earmark-break-fill mr-2"></i>Télécharger le reçu
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import jsPDF from 'jspdf';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const contract = ref(props.contract);

const generateReceipt = () => {
    const doc = new jsPDF();
    const company = props.auth.user.company;
    const tenant = contract.value.tenant;
    const paymentDate = new Date().toLocaleDateString('fr-FR');

    // Ajout du titre
    doc.setFontSize(18);
    doc.text('REÇU DE PAIEMENT', 105, 20, null, null, 'center');

    // Informations sur le bailleur et le locataire
    doc.setFontSize(12);
    doc.text('Bailleur :', 20, 40);
    doc.text(`${company.name}`, 20, 50);
    doc.text(`${company.address}`, 20, 60);
    doc.text(`Représenté par : ${company.representative_name}`, 20, 70);

    doc.text('Locataire :', 20, 90);
    doc.text(`${tenant.first_name} ${tenant.last_name}`, 20, 100);
    doc.text(`${tenant.address}`, 20, 110);

    doc.text('Détails du paiement :', 20, 130);
    doc.text(`Date de paiement : ${paymentDate}`, 20, 140);
    doc.text(`Montant payé : ${formatCurrency(contract.value.rent_amount)} CFA`, 20, 150);
    doc.text(`Pour le mois de : ${new Date().toLocaleString('fr-FR', { month: 'long', year: 'numeric' })}`, 20, 160);

    doc.text('Signature :', 20, 180);

    // Ajouter plus de contenu selon les besoins

    // Enregistrer le document PDF
    doc.save(`Reçu_Paiement_${tenant.last_name}_${tenant.first_name}.pdf`);
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'CFA' }).format(amount);
};
</script>
