<template>
    <div>
        <button @click="generateInvoicePDF" class="btn-primary">
            <i class="fas fa-download mr-2"></i>
            Télécharger la facture
        </button>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

const { props } = usePage();
const contract = computed(() => props.contract || {});
const rentalApplication = computed(() => props.rentalApplication || {});

const invoiceNumber = ref('INV-' + Math.floor(Math.random() * 1000000));
const invoiceDate = ref(new Date());

const monthlyRent = computed(() => contract.value.property.price);
const calculatedTVA = computed(() => monthlyRent.value * 0.18);
const calculatedTOM = computed(() => monthlyRent.value * 0.05);
const totalRent = computed(() => monthlyRent.value + calculatedTVA.value + calculatedTOM.value);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-SN', { style: 'currency', currency: 'XOF' }).format(value);
};

const generateInvoicePDF = () => {
    const doc = new jsPDF();
    const pageWidth = doc.internal.pageSize.width;
    const pageHeight = doc.internal.pageSize.height;

    // Add Header and Footer
    const addHeaderAndFooter = () => {
        // Header
        doc.setFillColor(52, 73, 94);
        doc.rect(0, 0, pageWidth, 20, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
        doc.text('Facture de Location', pageWidth / 2, 12, { align: 'center' });

        // Footer
        doc.setFillColor(52, 73, 94);
        doc.rect(0, pageHeight - 15, pageWidth, 15, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(8);
        doc.setFont("helvetica", "normal");
        doc.text('Document généré automatiquement - Tous droits réservés', pageWidth - 10, pageHeight - 10, { align: 'right' });
    };

    // Add Header and Footer
    addHeaderAndFooter();

    // Invoice Information
    doc.setFontSize(10);
    doc.setTextColor(0, 0, 0);
    doc.text(`Facture N° : ${invoiceNumber.value}`, 20, 30);
    doc.text(`Date : ${format(invoiceDate.value, 'dd MMMM yyyy', { locale: fr })}`, 20, 35);

    // Landlord Information
    doc.setFontSize(10);
    doc.text('Bailleur :', 20, 45);
    doc.text(props.contract.property.landlord.name, 20, 50);
    doc.text(props.contract.property.landlord.address, 20, 55);

    // Tenant Information
    doc.text('Locataire :', 120, 45);
    doc.text(props.contract.tenant.name, 120, 50);
    doc.text(props.contract.tenant.address, 120, 55);

    // Invoice Details
    doc.setFillColor(240, 240, 240);
    doc.rect(20, 65, pageWidth - 40, 20, 'F');
    doc.setFont("helvetica", "bold");
    doc.text('Description', 25, 75);
    doc.text('Montant', pageWidth - 25, 75, { align: 'right' });

    doc.setFont("helvetica", "normal");
    doc.text('Loyer mensuel', 25, 85);
    doc.text(formatCurrency(monthlyRent.value), pageWidth - 25, 85, { align: 'right' });

    doc.text('TVA (18%)', 25, 95);
    doc.text(formatCurrency(calculatedTVA.value), pageWidth - 25, 95, { align: 'right' });

    doc.text('Taxe sur les Ordures Ménagères (TOM)', 25, 105);
    doc.text(formatCurrency(calculatedTOM.value), pageWidth - 25, 105, { align: 'right' });

    doc.setFont("helvetica", "bold");
    doc.text('Total', 25, 115);
    doc.text(formatCurrency(totalRent.value), pageWidth - 25, 115, { align: 'right' });

    // Payment Terms
    doc.setFont("helvetica", "normal");
    doc.text('Conditions de paiement : Paiement dû dans les 5 jours suivant la réception de la facture.', 20, 130);

    // Save PDF
    doc.save(`Facture_${invoiceNumber.value}.pdf`);
};
</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
}
</style>
