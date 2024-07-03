<template>
    <AppLayout :title="'Contrat de Location de ' + contract.tenant.first_name + ' ' + contract.tenant.last_name">
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:p-8">
                        <Link :href="route('contracts.index')"
                            class="inline-flex items-center mb-6 text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                        </Link>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0 flex items-center">
                                <i class="fas fa-file-contract mr-3 text-indigo-600"></i>
                                Contrat de Location
                            </h1>
                            <button @click="downloadPDF" class="btn-primary">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger le contrat
                            </button>
                        </div>

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
                                <ContractDetailItem v-if="contract.contract_type === 'commercial'" icon="briefcase" label="Nom de l'entreprise" :value="contract.company_name" />
                                <ContractDetailItem v-if="contract.contract_type === 'commercial'" icon="user-tie" label="Représentant de l'entreprise" :value="contract.representative_name" />
                                <ContractDetailItem v-if="contract.contract_type === 'commercial'" icon="book" label="Registre de commerce" :value="contract.trade_register" />
                                <ContractDetailItem icon="money-bill-wave" label="Loyer mensuel" :value="formatCurrency(monthlyRent)" />
                                <ContractDetailItem icon="percent" label="TVA (18%)" :value="formatCurrency(calculatedTVA)" />
                                <ContractDetailItem icon="trash-alt" label="TOM" :value="formatCurrency(calculatedTOM)" />
                                <ContractDetailItem icon="hand-holding-usd" label="Total à payer" :value="formatCurrency(totalRent)" />
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-file-alt mr-2 text-indigo-600"></i> Articles du Contrat
                            </h2>
                            <div class="bg-white p-4 rounded-lg shadow-md">
                                <div v-for="article in articles" :key="article.title" class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ article.title }}</h3>
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ article.content }}</p>
                                </div>
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
import ContractDetailItem from '@/Components/ContractDetailItem.vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

const { props } = usePage();
const contract = computed(() => props.contract || {});
const rentalApplication = computed(() => props.rentalApplication || {});

const tenantName = computed(() => `${contract.value.tenant.first_name} ${contract.value.tenant.last_name}`);
const propertyName = computed(() => contract.value.property?.name || 'N/A');
const landlordName = computed(() => contract.value.property?.landlord?.first_name + ' ' + contract.value.property?.landlord?.last_name || 'N/A');

const monthlyRent = computed(() => contract.value.property.price);
const calculatedTVA = computed(() => monthlyRent.value * 0.18);
const calculatedTOM = computed(() => monthlyRent.value * 0.05);
const totalRent = computed(() => + monthlyRent.value + calculatedTVA.value + calculatedTOM.value);

const endDate = computed(() => {
    const startDate = new Date(rentalApplication.value.start_date);
    const duration = rentalApplication.value.duration;
    return format(new Date(startDate.setMonth(startDate.getMonth() + duration)), 'yyyy-MM-dd', { locale: fr });
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-SN', { style: 'currency', currency: 'XOF' }).format(value);
};

const articles = ref([
    {
        title: 'Article 1 - Objet du contrat',
        content: `Le présent contrat a pour objet la location d'un local situé à ${contract.value.property?.address || 'Adresse non spécifiée'} et destiné à usage ${contract.value.contract_type || 'non spécifié'}.\n\nLe bailleur, ${landlordName.value}, loue au locataire, ${tenantName.value}, qui accepte, les lieux désignés ci-après, constitués de ${contract.value.property?.description || 'Description non disponible'}. Le locataire ne pourra en aucun cas utiliser les lieux pour un autre usage que celui mentionné ci-dessus, conformément à l'article 544 du Code des Obligations Civiles et Commerciales du Sénégal.`,
    },
    {
        title: 'Article 2 - Durée de la location',
        content: `La durée du présent contrat est de ${rentalApplication.value.duration || 'non spécifiée'} mois à compter du ${contract.value.start_date || 'date non spécifiée'} jusqu'au ${endDate.value}.\n\nLe présent bail est consenti et accepté pour une durée de ${rentalApplication.value.duration || 'non spécifiée'} mois renouvelable par tacite reconduction, sauf dénonciation par l'une des parties par lettre recommandée avec accusé de réception, respectant un préavis de 3 mois, conformément à l'article 571 du Code des Obligations Civiles et Commerciales du Sénégal.`,
    },
    {
        title: 'Article 3 - Loyer et charges',
        content: `Le loyer mensuel est fixé à ${formatCurrency(monthlyRent.value)} FCFA, payable à l'avance, le 5 de chaque mois.\n\nEn plus du loyer, le locataire devra s'acquitter de la TVA au taux de 18%, soit un montant de ${formatCurrency(calculatedTVA.value)} FCFA, ainsi que de la Taxe sur les Ordures Ménagères (TOM) d'un montant de ${formatCurrency(calculatedTOM.value)} FCFA.\n\nLe montant total à payer mensuellement s'élève donc à ${formatCurrency(totalRent.value)} FCFA.\n\nLe locataire devra également s'acquitter des charges locatives comprenant ${contract.value.charges_description || 'description non disponible'}, dont le montant est estimé à ${formatCurrency(contract.value.charges_amount) || 'montant non spécifié'} FCFA.`,
    },
    {
        title: 'Article 4 - Obligations du locataire',
        content: `Le locataire s'engage à utiliser les lieux loués conformément à leur destination et à respecter les règles de bon voisinage.\n\nIl ne pourra apporter aucune modification aux lieux sans l'accord écrit du bailleur. Il devra également s'assurer du bon entretien des lieux et informer le bailleur de toute dégradation ou problème nécessitant une intervention.`,
    },
    {
        title: 'Article 5 - Obligations du bailleur',
        content: `Le bailleur s'engage à délivrer les lieux loués en bon état de réparations locatives et d'usage.\n\nIl devra également effectuer toutes les réparations nécessaires à l'entretien normal et à la mise en conformité des lieux en cas de nouvelles réglementations. Le bailleur devra garantir au locataire une jouissance paisible des lieux loués.`,
    },
    {
        title: 'Article 6 - Entretien et réparations',
        content: `Le locataire prendra à sa charge l'entretien courant des lieux, incluant les menues réparations et le remplacement des équipements défectueux à l'exception de ceux listés comme devant être entretenus par le bailleur.\n\nLe bailleur prendra à sa charge les réparations importantes nécessaires au maintien en état des lieux loués, incluant les grosses réparations et le remplacement des installations principales.`,
    },
    {
        title: 'Article 7 - Cession et sous-location',
        content: `Le locataire ne pourra céder son droit au bail ni sous-louer tout ou partie des lieux sans l'accord écrit préalable du bailleur.\n\nToute cession ou sous-location effectuée sans cet accord pourra entraîner la résiliation immédiate du contrat par le bailleur.`,
    },
    {
        title: 'Article 8 - Résiliation du contrat',
        content: `En cas de manquement par l'une des parties à ses obligations contractuelles, l'autre partie pourra demander la résiliation du contrat par voie judiciaire, après mise en demeure restée sans effet.\n\nLa résiliation pourra être prononcée de plein droit en cas de non-paiement du loyer ou des charges locatives.`,
    },
    {
        title: 'Article 9 - Clause résolutoire',
        content: `À défaut de paiement d'un seul terme du loyer à son échéance, ou en cas de non-respect des obligations contractuelles par le locataire, le présent contrat sera résilié de plein droit un mois après une mise en demeure restée infructueuse.\n\nLe bailleur pourra alors demander l'expulsion du locataire.`,
    },
    {
        title: 'Article 10 - État des lieux',
        content: `Un état des lieux contradictoire sera établi à l'entrée et à la sortie du locataire.\n\nLe locataire devra rendre les lieux dans l'état où ils ont été reçus, sauf vétusté normale. Tout dégât ou détérioration constaté sera à la charge du locataire, sauf en cas de force majeure.`,
    },
    {
        title: 'Article 11 - Assurances',
        content: `Le locataire s'engage à souscrire une assurance couvrant les risques locatifs (incendie, dégâts des eaux, responsabilité civile, etc.) et à en justifier au bailleur lors de la remise des clés puis annuellement.\n\nLe bailleur devra également souscrire une assurance couvrant les risques liés à la propriété des lieux.`,
    },
    {
        title: 'Article 12 - Litiges',
        content: `En cas de litige relatif à l'interprétation ou à l'exécution du présent contrat, les parties s'engagent à rechercher une solution amiable avant de saisir les tribunaux compétents.\n\nÀ défaut d'accord amiable, le litige sera porté devant les juridictions compétentes du lieu de situation de l'immeuble.`,
    },
    {
        title: 'Article 13 - Dispositions diverses',
        content: `Toutes les modifications du présent contrat devront être faites par écrit et signées par les deux parties.\n\nEn cas de contradiction entre les documents contractuels, le présent contrat prévaudra. Si une clause du présent contrat était déclarée nulle ou inapplicable, les autres dispositions resteraient en vigueur.`,
    },
]);

const downloadPDF = () => {
    const doc = new jsPDF();
    const pageWidth = doc.internal.pageSize.width;
    const pageHeight = doc.internal.pageSize.height;

    // Fonction pour ajouter un en-tête et un pied de page
    const addHeaderAndFooter = (pageNumber) => {
        // En-tête
        doc.setFillColor(52, 73, 94);
        doc.rect(0, 0, pageWidth, 20, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
        doc.text('Contrat de Location au Sénégal', pageWidth / 2, 12, { align: 'center' });

        // Logo (à remplacer par votre propre logo)
        doc.addImage('data:image/png;base64,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', 'PNG', 10, 2, 16, 16);

        // Pied de page
        doc.setFillColor(52, 73, 94);
        doc.rect(0, pageHeight - 15, pageWidth, 15, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(8);
        doc.setFont("helvetica", "normal");
        doc.text(`Page ${pageNumber}`, pageWidth / 2, pageHeight - 10, { align: 'center' });
        doc.text('Document généré automatiquement - Tous droits réservés', pageWidth - 10, pageHeight - 10, { align: 'right' });
    };

    // Fonction pour ajouter du contenu avec pagination
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

    // Ajouter l'en-tête et le pied de page à la première page
    addHeaderAndFooter(1);

    // Titre
    doc.setFontSize(22);
    doc.setTextColor(52, 73, 94);
    doc.setFont("helvetica", "bold");
    doc.text('CONTRAT DE LOCATION', pageWidth / 2, 40, { align: 'center' });

    // Sous-titre
    doc.setFontSize(14);
    doc.setTextColor(100, 100, 100);
    doc.text('Bail à usage d\'habitation au Sénégal', pageWidth / 2, 50, { align: 'center' });

    // Informations du contrat
    let y = 70;
    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.setFont("helvetica", "bold");
    y = addContent(`Numéro de contrat: ${contract.value.id || 'Non spécifié'}`, y);
    y = addContent(`Date de début: ${format(new Date(contract.value.start_date), 'dd MMMM yyyy', { locale: fr })}`, y);
    y = addContent(`Date de fin: ${format(new Date(endDate.value), 'dd MMMM yyyy', { locale: fr })}`, y, 12, "bold");
    y += 10;

    // Parties du contrat
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

    // Détails de la propriété
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

    // Conditions financières
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

    // Articles du contrat
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

    // Signatures
    doc.addPage();
    addHeaderAndFooter(doc.internal.getNumberOfPages());
    y = 40;

    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.text(`Fait à ${contract.value.company_address || 'lieu non spécifié'}, le ${format(new Date(contract.value.start_date), 'dd MMMM yyyy', { locale: fr })}`, 20, y);
    y += 20;

    // Cadres de signature
    doc.setDrawColor(52, 73, 94);
    doc.rect(20, y, 80, 40);
    doc.rect(110, y, 80, 40);

    doc.setFontSize(10);
    doc.text('Le Bailleur', 60, y - 5, { align: 'center' });
    doc.text('Le Locataire', 150, y - 5, { align: 'center' });
    doc.text('(Signature précédée de la mention "Lu et approuvé")', pageWidth / 2, y + 50, { align: 'center' });

    // Sauvegarde du PDF
    doc.save(`Contrat_de_Location_${tenantName.value.replace(' ', '_')}.pdf`);
};

</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150;
}
</style>
