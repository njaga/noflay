<template>
    <div>
        <button @click="downloadCommercialPDF" class="btn-primary">
            <i class="fas fa-download mr-2"></i>
            Télécharger le contrat commercial
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

const tenantName = computed(() => `${contract.value.tenant.first_name} ${contract.value.tenant.last_name}`);
const propertyName = computed(() => contract.value.property?.name || 'N/A');
const landlordName = computed(() => `${contract.value.property?.landlord?.first_name} ${contract.value.property?.landlord?.last_name}` || 'N/A');

const monthlyRent = computed(() => contract.value.property.price);
const calculatedTVA = computed(() => monthlyRent.value * 0.18);
const calculatedTOM = computed(() => monthlyRent.value * 0.05);
const totalRent = computed(() => monthlyRent.value + calculatedTVA.value + calculatedTOM.value);

const endDate = computed(() => {
    const startDate = new Date(rentalApplication.value.start_date);
    const duration = rentalApplication.value.duration;
    return format(new Date(startDate.setMonth(startDate.getMonth() + duration)), 'yyyy-MM-dd', { locale: fr });
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-SN', { style: 'currency', currency: 'XOF' }).format(value);
};

const articles = ref([
    // articles content...
]);

const downloadCommercialPDF = () => {
    const doc = new jsPDF();
    const pageWidth = doc.internal.pageSize.width;
    const pageHeight = doc.internal.pageSize.height;

    const addHeaderAndFooter = (pageNumber) => {
        doc.setFillColor(52, 73, 94);
        doc.rect(0, 0, pageWidth, 20, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
        doc.text('Contrat de Location Commercial', pageWidth / 2, 12, { align: 'center' });

        doc.setFillColor(52, 73, 94);
        doc.rect(0, pageHeight - 15, pageWidth, 15, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(8);
        doc.setFont("helvetica", "normal");
        doc.text(`Page ${pageNumber}`, pageWidth / 2, pageHeight - 10, { align: 'center' });
        doc.text('Document généré automatiquement - Tous droits réservés', pageWidth - 10, pageHeight - 10, { align: 'right' });
    };

    const addContent = (content, startY, fontSize = 10, fontStyle = "normal") => {
        doc.setFontSize(fontSize);
        doc.setFont("helvetica", fontStyle);
        const splitText = doc.splitTextToSize(content, pageWidth - 40);
        let y = startY;

        splitText.forEach(line => {
            if (y > pageHeight - 25) {
                doc.addPage();
                y = 30;
                addHeaderAndFooter(doc.internal.getNumberOfPages());
            }
            doc.text(line, 20, y);
            y += fontSize * 0.5;
        });

        return y;
    };

    addHeaderAndFooter(1);

    doc.setFontSize(22);
    doc.setTextColor(52, 73, 94);
    doc.setFont("helvetica", "bold");
    doc.text('CONTRAT DE LOCATION COMMERCIAL', pageWidth / 2, 40, { align: 'center' });

    doc.setFontSize(14);
    doc.setTextColor(100, 100, 100);
    doc.text('Bail à usage commercial au Sénégal', pageWidth / 2, 50, { align: 'center' });

    let y = 70;
    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.setFont("helvetica", "bold");
    y = addContent(`Numéro de contrat: ${contract.value.id || 'Non spécifié'}`, y);
    y = addContent(`Date de début: ${format(new Date(contract.value.start_date), 'dd MMMM yyyy', { locale: fr })}`, y);
    y = addContent(`Date de fin: ${format(new Date(endDate.value), 'dd MMMM yyyy', { locale: fr })}`, y, 12, "bold");
    y += 10;

    doc.setFillColor(240, 240, 240);
    doc.rect(20, y, pageWidth - 40, 50, 'F');
    y += 5;
    doc.setFontSize(14);
    doc.setTextColor(52, 73, 94);
    y = addContent("ENTRE LES SOUSSIGNÉS", y, 14, "bold");
    y += 5;

    const partiesContent =
        `Le bailleur : ${landlordName.value}\n` +
        `Adresse : ${contract.value.property?.landlord?.address || 'Non spécifiée'}\n` +
        `CI/Passeport : ${contract.value.property?.landlord?.id_number || 'Non spécifié'}\n\n` +
        `ET\n\n` +
        `Le locataire : ${tenantName.value}\n` +
        `Adresse : ${contract.value.tenant?.address || 'Non spécifiée'}\n` +
        `CI/Passeport : ${contract.value.tenant?.id_number || 'Non spécifié'}`;

    y = addContent(partiesContent, y, 10, "normal");
    y += 15;

    doc.setFillColor(240, 240, 240);
    doc.rect(20, y, pageWidth - 40, 40, 'F');
    y += 5;
    doc.setFontSize(14);
    doc.setTextColor(52, 73, 94);
    y = addContent("DESCRIPTION DU BIEN LOUÉ", y, 14, "bold");
    y += 5;

    const propertyContent =
        `Adresse : ${contract.value.property?.address || 'Non spécifiée'}\n` +
        `Type de bien : ${contract.value.property?.type || 'Non spécifié'}\n` +
        `Description : ${contract.value.property?.description || 'Non disponible'}`;

    y = addContent(propertyContent, y, 10, "normal");
    y += 15;

    doc.setFillColor(240, 240, 240);
    doc.rect(20, y, pageWidth - 40, 60, 'F');
    y += 5;
    doc.setFontSize(14);
    doc.setTextColor(52, 73, 94);
    y = addContent("CONDITIONS FINANCIÈRES", y, 14, "bold");
    y += 5;

    const financialContent =
        `Loyer mensuel : ${formatCurrency(monthlyRent.value)}\n` +
        `TVA (18%) : ${formatCurrency(calculatedTVA.value)}\n` +
        `Taxe sur les Ordures Ménagères (TOM) : ${formatCurrency(calculatedTOM.value)}\n` +
        `Total à payer mensuellement : ${formatCurrency(totalRent.value)}\n` +
        `Dépôt de garantie : ${formatCurrency(contract.value.deposit_amount || 0)}`;

    y = addContent(financialContent, y, 10, "normal");
    y += 15;

    doc.addPage();
    addHeaderAndFooter(doc.internal.getNumberOfPages());
    y = 30;

    doc.setFontSize(16);
    doc.setTextColor(52, 73, 94);
    y = addContent("CLAUSES ET CONDITIONS", y, 16, "bold");
    y += 10;

    articles.value.forEach((article, index) => {
        doc.setFontSize(12);
        doc.setTextColor(52, 73, 94);
        y = addContent(article.title, y, 12, "bold");
        y += 5;

        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        y = addContent(article.content, y, 10, "normal");
        y += 10;
    });

    doc.addPage();
    addHeaderAndFooter(doc.internal.getNumberOfPages());
    y = 40;

    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.text(`Fait à ${contract.value.company_address || 'lieu non spécifié'}, le ${format(new Date(contract.value.start_date), 'dd MMMM yyyy', { locale: fr })}`, 20, y);
    y += 20;

    doc.setDrawColor(52, 73, 94);
    doc.rect(20, y, 80, 40);
    doc.rect(110, y, 80, 40);

    doc.setFontSize(10);
    doc.text('Le Bailleur', 60, y - 5, { align: 'center' });
    doc.text('Le Locataire', 150, y - 5, { align: 'center' });
    doc.text('(Signature précédée de la mention "Lu et approuvé")', pageWidth / 2, y + 50, { align: 'center' });

    doc.save(`Contrat_de_Location_Commercial_${tenantName.value.replace(' ', '_')}.pdf`);
};
</script>

<style scoped>
.btn-primary {
  @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out durée-150;
}
</style>
