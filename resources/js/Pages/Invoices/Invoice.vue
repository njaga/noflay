<template>
    <AppLayout :title="'Facture de Location'">
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:p-8">
                        <Link :href="route('invoices.index')"
                            class="inline-flex items-center mb-6 text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                        </Link>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0 flex items-center">
                                <i class="fas fa-file-invoice mr-3 text-indigo-600"></i>
                                Facture de Location
                            </h1>
                            <button @click="generateInvoicePDF" class="btn-primary">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger la facture
                            </button>
                        </div>

                        <div class="bg-gradient-to-r from-indigo-100 to-blue-100 p-6 rounded-lg mb-8 shadow-sm">
                            <h2 class="text-xl font-semibold text-indigo-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Détails de la Facture
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <InvoiceDetailItem icon="file-alt" label="Numéro de Facture" :value="invoiceNumber" />
                                <InvoiceDetailItem icon="calendar-alt" label="Date" :value="formattedInvoiceDate" />
                                <InvoiceDetailItem icon="user" label="Locataire" :value="tenantName" />
                                <InvoiceDetailItem icon="building" label="Propriété" :value="propertyName" />
                                <InvoiceDetailItem icon="money-bill-wave" label="Loyer mensuel" :value="formattedMonthlyRent" />
                                <InvoiceDetailItem icon="percent" label="TVA (18%)" :value="formattedTVA" />
                                <InvoiceDetailItem icon="trash-alt" label="TOM" :value="formattedTOM" />
                                <InvoiceDetailItem icon="hand-holding-usd" label="Total à payer" :value="formattedTotalRent" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

const { props } = usePage();
const contract = computed(() => props.contract || {});
const invoiceNumber = ref('INV-' + Math.floor(Math.random() * 1000000));
const invoiceDate = ref(new Date());

const tenantName = computed(() => `${contract.value.tenant.first_name} ${contract.value.tenant.last_name}`);
const propertyName = computed(() => contract.value.property?.name || 'N/A');
const landlordName = computed(() => contract.value.property?.landlord?.first_name + ' ' + contract.value.property?.landlord?.last_name || 'N/A');

const monthlyRent = computed(() => contract.value.property.price);
const calculatedTVA = computed(() => monthlyRent.value * 0.18);
const calculatedTOM = computed(() => monthlyRent.value * 0.05);
const totalRent = computed(() => monthlyRent.value + calculatedTVA.value + calculatedTOM.value);

const formattedInvoiceDate = computed(() => format(invoiceDate.value, 'dd MMMM yyyy', { locale: fr }));
const formattedMonthlyRent = computed(() => formatCurrency(monthlyRent.value));
const formattedTVA = computed(() => formatCurrency(calculatedTVA.value));
const formattedTOM = computed(() => formatCurrency(calculatedTOM.value));
const formattedTotalRent = computed(() => formatCurrency(totalRent.value));

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-SN', { style: 'currency', currency: 'XOF' }).format(value);
};

const generateInvoicePDF = () => {
    const doc = new jsPDF();
    const pageWidth = doc.internal.pageSize.width;
    const pageHeight = doc.internal.pageSize.height;

    // Fonction pour ajouter un en-tête et un pied de page
    const addHeaderAndFooter = () => {
        // En-tête
        doc.setFillColor(52, 73, 94);
        doc.rect(0, 0, pageWidth, 20, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
        doc.text('Facture de Location', pageWidth / 2, 12, { align: 'center' });

        // Pied de page
        doc.setFillColor(52, 73, 94);
        doc.rect(0, pageHeight - 15, pageWidth, 15, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(8);
        doc.setFont("helvetica", "normal");
        doc.text('Document généré automatiquement - Tous droits réservés', pageWidth - 10, pageHeight - 10, { align: 'right' });
    };

    // Ajouter l'en-tête et le pied de page
    addHeaderAndFooter();

    // Informations de la facture
    doc.setFontSize(10);
    doc.setTextColor(0, 0, 0);
    doc.text(`Facture N° : ${invoiceNumber.value}`, 20, 30);
    doc.text(`Date : ${formattedInvoiceDate.value}`, 20, 35);

    // Informations du bailleur
    doc.setFontSize(10);
    doc.text('Bailleur :', 20, 45);
    doc.text(contract.value.property.landlord.name, 20, 50);
    doc.text(contract.value.property.landlord.address, 20, 55);

    // Informations du locataire
    doc.text('Locataire :', 120, 45);
    doc.text(contract.value.tenant.name, 120, 50);
    doc.text(contract.value.tenant.address, 120, 55);

    // Détails de la facture
    doc.setFillColor(240, 240, 240);
    doc.rect(20, 65, pageWidth - 40, 20, 'F');
    doc.setFont("helvetica", "bold");
    doc.text('Description', 25, 75);
    doc.text('Montant', pageWidth - 25, 75, { align: 'right' });

    doc.setFont("helvetica", "normal");
    doc.text('Loyer mensuel', 25, 85);
    doc.text(formattedMonthlyRent.value, pageWidth - 25, 85, { align: 'right' });

    doc.text('TVA (18%)', 25, 95);
    doc.text(formattedTVA.value, pageWidth - 25, 95, { align: 'right' });

    doc.text('Taxe sur les Ordures Ménagères (TOM)', 25, 105);
    doc.text(formattedTOM.value, pageWidth - 25, 105, { align: 'right' });

    doc.setFont("helvetica", "bold");
    doc.text('Total', 25, 115);
    doc.text(formattedTotalRent.value, pageWidth - 25, 115, { align: 'right' });

    // Conditions de paiement
    doc.setFont("helvetica", "normal");
    doc.text('Conditions de paiement : Paiement dû dans les 5 jours suivant la réception de la facture.', 20, 130);

    // Sauvegarde du PDF
    doc.save(`Facture_${invoiceNumber.value}.pdf`);
};
</script>

<style scoped>
.bg-gradient-to-r {
    background-image: linear-gradient(to right, #ebf8ff, #edf2f7);
}
.btn-primary {
    background-color: #4a76a8;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    text-align: center;
    font-size: 1rem;
    font-weight: 500;
    transition: background-color 0.3s ease;
}
.btn-primary:hover {
    background-color: #3b5998;
}
</style>
