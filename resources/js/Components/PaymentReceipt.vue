<template>
    <div>
      <button @click="generatePdf" class="bg-blue-500 text-white px-4 py-2 rounded">Télécharger le reçu</button>
    </div>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import jsPDF from 'jspdf';
  import 'jspdf-autotable';

  const { props } = usePage();
  const payment = props.payment;
  const contract = props.contract;
  const tenant = props.tenant;
  const property = props.property;

  const generatePdf = () => {
    const doc = new jsPDF();

    // Set document properties
    doc.setProperties({
      title: 'Reçu de Paiement',
    });

    // Add title
    doc.setFontSize(20);
    doc.text('Reçu de Paiement', 14, 22);

    // Add details
    doc.setFontSize(12);
    doc.text(`Nom du locataire: ${tenant.first_name} ${tenant.last_name}`, 14, 40);
    doc.text(`Propriété: ${property.name}`, 14, 50);
    doc.text(`Adresse de la propriété: ${property.address}`, 14, 60);
    doc.text(`Montant payé: ${payment.amount} CFA`, 14, 70);
    doc.text(`Date de paiement: ${formatDate(payment.payment_date)}`, 14, 80);

    // Add table for contract details
    doc.autoTable({
      startY: 90,
      head: [['ID du Contrat', 'Type de Contrat', 'Date de début', 'Date de fin']],
      body: [
        [contract.id, contract.contract_type, formatDate(contract.start_date), formatDate(contract.end_date)],
      ],
    });

    // Add footer
    doc.setFontSize(10);
    doc.text('Merci pour votre paiement.', 14, doc.internal.pageSize.height - 30);

    // Save the PDF
    doc.save(`Reçu_Paiement_${payment.id}.pdf`);
  };

  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', options);
  };
  </script>
