<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
      <div :class="['bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300',
                    isExpanded ? 'w-full max-w-4xl h-5/6' : 'w-96 h-64']">
        <div class="flex justify-between items-center p-4 bg-gray-100">
          <h2 class="text-xl font-bold">Mandat de Gérance</h2>
          <div class="flex space-x-2">
            <button @click="generatePDF" class="p-2 hover:bg-gray-200 rounded-full" title="Télécharger">
              <Download size="20" />
            </button>
            <button @click="printMandat" class="p-2 hover:bg-gray-200 rounded-full" title="Imprimer">
              <Printer size="20" />
            </button>
            <button @click="toggleExpand" class="p-2 hover:bg-gray-200 rounded-full" :title="isExpanded ? 'Réduire' : 'Agrandir'">
              <component :is="isExpanded ? 'Minimize2' : 'Maximize2'" size="20" />
            </button>
            <button @click="$emit('close')" class="p-2 hover:bg-gray-200 rounded-full" title="Fermer">
              <X size="20" />
            </button>
          </div>
        </div>
        <div ref="mandatContent" class="p-6 overflow-auto" :style="{ maxHeight: isExpanded ? 'calc(100% - 64px)' : '200px' }">
          <h2 class="text-2xl font-bold mb-4">Mandat de Gérance</h2>
          <p v-if="!company.name" class="text-red-500">Informations de l'entreprise non disponibles</p>
          <div v-else>
            <p class="mb-4">
              Entre les soussignés :<br>
              <strong>{{ company.name }}</strong>, représentée par {{ company.representative }},
              demeurant à {{ company.address }},<br>
              Ci-après dénommée "le Mandant",<br><br>
              Et<br>
              <strong>{{ landlord.first_name }} {{ landlord.last_name }}</strong>, demeurant à {{ landlord.address }},<br>
              Ci-après dénommé "le Mandataire".
            </p>

            <h3 class="text-xl font-semibold mt-6 mb-4">Il a été convenu ce qui suit :</h3>

            <h4 class="text-lg font-semibold mt-4">Article 1 : Objet du Mandat</h4>
            <p>
              Le présent mandat a pour objet la gestion locative des biens immobiliers suivants appartenant au Mandant :
            </p>
            <ul class="list-disc pl-6 mb-4">
              <li v-for="property in properties" :key="property.id">{{ property.name }} - {{ property.address }}</li>
            </ul>

            <h4 class="text-lg font-semibold mt-4">Article 2 : Durée du Mandat</h4>
            <p class="mb-4">
              Le présent mandat est conclu pour une durée de {{ landlord.contract_duration }} mois, à compter de la signature du présent contrat. Il se renouvellera par tacite reconduction pour une durée équivalente, sauf dénonciation par l'une ou l'autre des parties, par lettre recommandée avec accusé de réception, trois mois avant son terme.
            </p>

            <h4 class="text-lg font-semibold mt-4">Article 3 : Obligations du Mandataire</h4>
            <p class="mb-2">Le Mandataire s'engage à :</p>
            <ul class="list-disc pl-6 mb-4">
              <li>Assurer la gestion courante des biens immobiliers du Mandant.</li>
              <li>Rechercher des locataires et effectuer toutes les démarches nécessaires à la location des biens.</li>
              <li>Établir les baux et les états des lieux.</li>
              <li>Encaisser les loyers et charges et les reverser au Mandant.</li>
              <li>Assurer le suivi des travaux d'entretien et de réparation nécessaires.</li>
              <li>Représenter le Mandant dans toutes les assemblées de copropriété.</li>
              <li>Tenir une comptabilité régulière et transmettre au Mandant un compte-rendu de gestion trimestriel.</li>
            </ul>

            <h4 class="text-lg font-semibold mt-4">Article 4 : Obligations du Mandant</h4>
            <p class="mb-2">Le Mandant s'engage à :</p>
            <ul class="list-disc pl-6 mb-4">
              <li>Fournir au Mandataire tous les documents et informations nécessaires à l'exécution de sa mission.</li>
              <li>Informer le Mandataire de tout changement concernant sa situation ou celle des biens gérés.</li>
              <li>Ne pas intervenir dans la gestion des biens confiés au Mandataire.</li>
              <li>Régler les honoraires du Mandataire selon les conditions prévues à l'article 5.</li>
            </ul>

            <h4 class="text-lg font-semibold mt-4">Article 5 : Rémunération du Mandataire</h4>
            <p class="mb-4">
              En contrepartie de ses services, le Mandataire percevra une commission de {{ landlord.agency_percentage }}% sur les loyers encaissés, taxes et charges comprises. Cette commission sera prélevée mensuellement sur les sommes encaissées pour le compte du Mandant.
            </p>

            <h4 class="text-lg font-semibold mt-4">Article 6 : Responsabilité</h4>
            <p class="mb-4">
              Le Mandataire est responsable de sa gestion et doit rendre compte de celle-ci au Mandant. Il est tenu d'une obligation de moyens dans l'exécution de sa mission. Le Mandataire devra justifier d'une assurance professionnelle couvrant sa responsabilité civile.
            </p>

            <h4 class="text-lg font-semibold mt-4">Article 7 : Résiliation</h4>
            <p class="mb-4">
              Le présent mandat peut être résilié par l'une ou l'autre des parties, à tout moment, moyennant un préavis de trois mois notifié par lettre recommandée avec accusé de réception. En cas de manquement grave de l'une des parties à ses obligations, l'autre partie pourra mettre fin au contrat sans préavis, après mise en demeure restée sans effet pendant 15 jours.
            </p>

            <h4 class="text-lg font-semibold mt-4">Article 8 : Litiges</h4>
            <p class="mb-4">
              En cas de litige relatif à l'interprétation ou à l'exécution du présent mandat, les parties s'engagent à rechercher une solution amiable. À défaut d'accord, le litige sera porté devant les tribunaux compétents du lieu de situation des biens.
            </p>

            <h4 class="text-lg font-semibold mt-4">Article 9 : Élection de domicile</h4>
            <p class="mb-4">
              Pour l'exécution des présentes, les parties font élection de domicile aux adresses indiquées en tête du présent mandat.
            </p>

            <div class="mt-6">
              <p>Fait à {{ company.city }}, le {{ formatDate(new Date()) }}</p>
              <p class="mt-4">Le Mandant : ______________________</p>
              <p class="mt-4">Le Mandataire : ______________________</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  import { ref } from 'vue';
  import { Download, Printer, Maximize2, Minimize2, X } from 'lucide-vue-next';
  import jsPDF from 'jspdf';
  import html2canvas from 'html2canvas';

  export default {
    components: {
      Download,
      Printer,
      Maximize2,
      Minimize2,
      X
    },
    props: {
      landlord: {
        type: Object,
        required: true
      },
      properties: {
        type: Array,
        required: true
      },
      company: {
        type: Object,
        required: true
      },
    },
    setup(props, { emit }) {
      const isExpanded = ref(false);
      const mandatContent = ref(null);

      const formatDate = (date) => {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('fr-FR', options);
      };

      const generatePDF = async () => {
        const content = mandatContent.value;
        const canvas = await html2canvas(content);
        const imgData = canvas.toDataURL('image/png');

        const pdf = new jsPDF('p', 'mm', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();
        const imgWidth = canvas.width;
        const imgHeight = canvas.height;
        const ratio = Math.min(pdfWidth / imgWidth, pdfHeight / imgHeight);
        const imgX = (pdfWidth - imgWidth * ratio) / 2;
        const imgY = 30;

        pdf.addImage(imgData, 'PNG', imgX, imgY, imgWidth * ratio, imgHeight * ratio);
        pdf.save('mandat_gerance.pdf');
      };

      const printMandat = () => {
        const content = mandatContent.value.innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
          <html>
            <head>
              <title>Mandat de Gérance</title>
              <style>
                body { font-family: Arial, sans-serif; }
                @media print {
                  body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                }
              </style>
            </head>
            <body>${content}</body>
          </html>
        `);
        printWindow.document.close();
        printWindow.print();
      };

      const toggleExpand = () => {
        isExpanded.value = !isExpanded.value;
      };

      return {
        isExpanded,
        mandatContent,
        formatDate,
        generatePDF,
        printMandat,
        toggleExpand,
      };
    },
  };
  </script>

  <style scoped>
  @media print {
    .fixed { position: static; }
    .inset-0 { position: static; }
    .z-50 { z-index: auto; }
    .bg-gray-900 { background-color: transparent; }
    .bg-opacity-50 { background-color: transparent; }
    .shadow-lg { box-shadow: none; }
    .overflow-auto { overflow: visible; }
  }
  </style>
