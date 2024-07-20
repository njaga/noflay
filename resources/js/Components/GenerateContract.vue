<template>
    <button @click="generatePdf" class="btn-primary">
      <i class="fas fa-file-pdf mr-2"></i> Télécharger le contrat
    </button>
  </template>

  <script setup>
  import { ref } from 'vue';
  import jsPDF from 'jspdf';
  import 'jspdf-autotable';
  import { usePage } from '@inertiajs/vue3';

  const { props } = usePage();
  const contract = ref(props.contract);

  const generatePdf = () => {
    const doc = new jsPDF();
    const company = props.auth.user.company;
    const tenant = contract.value.tenant;
    const property = contract.value.property;
    const isHabitation = contract.value.contract_type === 'habitation';

    // Configuration du document
    doc.setProperties({
      title: `Contrat de Location - ${contract.value.file_number}`,
      subject: 'Contrat de Location',
      author: company.name,
      keywords: 'contrat, location, immobilier',
      creator: 'Système de Gestion Locative'
    });

    // Ajout de l'en-tête
    addHeader(doc, company);

    // Titre du document
    doc.setFontSize(22);
    doc.setTextColor(41, 98, 255); // Couleur indigo
    doc.text('CONTRAT DE LOCATION', 105, 40, null, null, 'center');
    doc.setFontSize(16);
    doc.setTextColor(75, 85, 99); // Couleur gris foncé
    doc.text(isHabitation ? "À USAGE D'HABITATION" : "À USAGE COMMERCIAL", 105, 50, null, null, 'center');

    // Informations sur le contrat
    addContractInfo(doc, contract.value, 60);

    // Informations sur le bailleur et le locataire
    addPartyInfo(doc, company, tenant, 90);

    // Articles du contrat
    const articles = getArticles(contract.value, property, isHabitation);
    addArticles(doc, articles, 140);

    // Espace pour les signatures
    addSignatureSpace(doc, company, tenant);

    // Pied de page
    addFooter(doc, company);

    // Enregistrer le document PDF
    doc.save(`Contrat_${contract.value.file_number}_${isHabitation ? 'habitation' : 'commercial'}.pdf`);
  };

  const addHeader = (doc, company) => {
    doc.setFillColor(236, 253, 245); // Couleur de fond vert pâle
    doc.rect(0, 0, 210, 30, 'F');
    doc.setFontSize(12);
    doc.setTextColor(6, 95, 70); // Couleur vert foncé
    doc.text(company.name, 10, 10);
    doc.setFontSize(10);
    doc.text(company.address, 10, 20);
  };

  const addContractInfo = (doc, contract, y) => {
    doc.setFontSize(12);
    doc.setTextColor(55, 65, 81); // Couleur gris
    doc.text(`Numéro de contrat : ${contract.file_number}`, 20, y);
    doc.text(`Date de début : ${formatDate(contract.start_date)}`, 20, y + 10);
    doc.text(`Date de fin : ${formatDate(contract.end_date)}`, 120, y + 10);
  };

  const addPartyInfo = (doc, company, tenant, y) => {
    doc.setFontSize(14);
    doc.setTextColor(31, 41, 55); // Couleur gris foncé
    doc.text('Entre les soussignés :', 20, y);

    doc.setFontSize(12);
    doc.setTextColor(55, 65, 81); // Couleur gris
    doc.text(`${company.name}, représentée par ${company.representative_name},`, 20, y + 10);
    doc.text(`en qualité de ${company.representative_title}, ci-après dénommée "le Bailleur",`, 20, y + 20);

    doc.text(`Et ${tenant.first_name} ${tenant.last_name}, demeurant à ${tenant.address},`, 20, y + 35);
    doc.text('ci-après dénommé "le Locataire",', 20, y + 45);
  };

  const addArticles = (doc, articles, startY) => {
    let y = startY;
    articles.forEach((article, index) => {
      if (y > 250) {
        doc.addPage();
        y = 20;
      }

      doc.setFontSize(14);
      doc.setTextColor(31, 41, 55); // Couleur gris foncé
      doc.text(article.title, 20, y);

      doc.setFontSize(11);
      doc.setTextColor(55, 65, 81); // Couleur gris
      const lines = doc.splitTextToSize(article.text, 170);
      doc.text(lines, 20, y + 10);

      y += 20 + (lines.length * 5);
    });
  };

  const addSignatureSpace = (doc, company, tenant) => {
    doc.addPage();
    let y = 20;

    doc.setFontSize(12);
    doc.setTextColor(31, 41, 55); // Couleur gris foncé
    doc.text('Fait à _____________________, le _____________________', 20, y);

    y += 30;
    doc.text('Signature du Bailleur :', 20, y);
    doc.text('Signature du Locataire :', 120, y);

    y += 40;
    doc.line(20, y, 90, y);
    doc.line(120, y, 190, y);

    y += 10;
    doc.setFontSize(10);
    doc.text(`${company.representative_name}`, 20, y);
    doc.text(`${tenant.first_name} ${tenant.last_name}`, 120, y);
  };

  const addFooter = (doc, company) => {
    const pageCount = doc.internal.getNumberOfPages();
    doc.setFontSize(8);
    doc.setTextColor(107, 114, 128); // Couleur gris clair

    for (let i = 1; i <= pageCount; i++) {
      doc.setPage(i);
      doc.text(`${company.name} - Page ${i} sur ${pageCount}`, 105, 287, null, null, 'center');
    }
  };

  const getArticles = (contract, property, isHabitation) => [
    {
      title: 'Article 1 : Objet du contrat',
      text: `Le Bailleur loue au Locataire, qui accepte, les locaux situés à ${property.address}. ${property.description}`
    },
    {
      title: 'Article 2 : Durée du contrat',
      text: `Le présent contrat est conclu pour une durée de ${contract.duration} mois à compter du ${formatDate(contract.start_date)}. Il prendra fin le ${formatDate(contract.end_date)}.`
    },
    {
      title: 'Article 3 : Loyer et charges',
      text: `Le loyer mensuel est fixé à ${formatCurrency(contract.rent_amount)}. Le Locataire s'acquittera également des charges suivantes : eau, électricité, et taxe d'habitation. Le paiement du loyer et des charges s'effectuera avant le 5 de chaque mois.`
    },
    {
      title: 'Article 4 : Dépôt de garantie',
      text: `À la signature du présent contrat, le Locataire versera la somme de ${formatCurrency(contract.caution_amount)} à titre de dépôt de garantie pour répondre des dégâts qui pourraient être causés aux locaux loués.`
    },
    {
      title: 'Article 5 : Obligations du bailleur',
      text: 'Le Bailleur est obligé : de délivrer au Locataire le logement en bon état d\'usage et de réparation ainsi que les équipements mentionnés au contrat de location en bon état de fonctionnement ; d\'assurer au Locataire la jouissance paisible du logement et de le garantir des vices ou défauts qui en empêchent l\'usage.'
    },
    {
      title: 'Article 6 : Obligations du locataire',
      text: 'Le Locataire est obligé de payer le loyer et les charges récupérables aux termes convenus ; d\'user paisiblement des locaux loués suivant la destination qui leur a été donnée par le contrat de location ; de répondre des dégradations et pertes qui surviennent pendant la durée du contrat dans les locaux dont il a la jouissance exclusive.'
    },
    {
      title: 'Article 7 : Assurance',
      text: 'Le Locataire est tenu d\'assurer les locaux loués et de justifier du paiement des primes d\'assurance à chaque anniversaire du contrat.'
    },
    {
      title: 'Article 8 : Clause résolutoire',
      text: 'À défaut de paiement au terme convenu, de tout ou partie du loyer ou des charges, ou à défaut de versement du dépôt de garantie, le présent contrat sera résilié de plein droit.'
    },
    {
      title: 'Article 9 : Élection de domicile',
      text: 'Pour l\'exécution des obligations visées au présent contrat, le Bailleur fait élection de domicile en sa demeure et le Locataire dans les lieux loués.'
    }
  ];

  const formatDate = (dateString) => {
    if (!dateString || dateString === 'N/A') return 'N/A';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return isNaN(date) ? 'Invalid Date' : date.toLocaleDateString('fr-FR', options);
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(amount);
  };

  </script>

  <style scoped>
  .btn-primary {
    @apply inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-md shadow-md hover:bg-indigo-700 transition duration-150 ease-in-out;
  }
  </style>
