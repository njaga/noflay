<template>
    <AppLayout title="Centre d'Aide Interactif">
        <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-5xl font-extrabold text-gray-900 mb-8 text-center">
                    Centre d'Aide
                </h1>

                <!-- Barre de recherche moderne -->
                <div class="mb-12 relative max-w-2xl mx-auto">
                    <input v-model="searchQuery" @input="updateSearchSuggestions" type="text"
                        placeholder="Comment pouvons-nous vous aider aujourd'hui ?"
                        class="w-full p-4 pr-12 text-gray-900 bg-white shadow-lg rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-gray-400" />
                    <MagnifyingGlassIcon class="absolute right-4 top-4 h-6 w-6 text-gray-400" />
                </div>

                <!-- Suggestions de recherche -->
                <div v-if="searchSuggestions.length > 0"
                    class="absolute z-10 w-full max-w-2xl mx-auto mt-2 bg-white rounded-md shadow-lg">
                    <ul class="py-1">
                        <li v-for="suggestion in searchSuggestions" :key="suggestion"
                            @click="selectSuggestion(suggestion)"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-700">
                            {{ suggestion }}
                        </li>
                    </ul>
                </div>

                <!-- Catégories de FAQ avec icônes -->
                <div class="mb-12">
                    <div class="flex flex-wrap justify-center gap-4">
                        <button v-for="category in categories" :key="category.name"
                            @click="selectCategory(category.name)" :class="[
                                'px-6 py-3 rounded-full transition-all duration-300 flex items-center space-x-2',
                                selectedCategory === category.name
                                    ? 'bg-indigo-600 text-white shadow-lg transform scale-105'
                                    : 'bg-white text-gray-700 hover:bg-gray-50',
                            ]">
                            <component :is="category.icon" class="h-5 w-5" />
                            <span>{{ category.name }}</span>
                        </button>
                    </div>
                </div>

                <!-- Liste des FAQs avec animation et design moderne -->
                <TransitionGroup name="list" tag="div" class="space-y-6">
                    <div v-for="faq in paginatedFaqs" :key="faq.id"
                        class="bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <div @click="toggleFaq(faq.id)" class="p-6 cursor-pointer flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-900">
                                {{ faq.question }}
                            </h2>
                            <ChevronDownIcon :class="[
                                'h-6 w-6 text-gray-500 transition-transform duration-300',
                                { 'rotate-180': openedFaq === faq.id },
                            ]" />
                        </div>
                        <div v-show="openedFaq === faq.id" class="p-6 bg-gray-50">
                            <p class="text-gray-700 whitespace-pre-line">
                                {{ faq.answer }}
                            </p>
                            <div class="mt-4 flex justify-between items-center">
                                <div class="flex space-x-4">
                                    <button @click="rateFaq(faq.id, 'helpful')"
                                        class="flex items-center space-x-1 text-green-600 hover:text-green-700 transition-colors duration-200">
                                        <HandThumbUpIcon class="h-6 w-6" />
                                        <span>{{ faq.likes }}</span>
                                    </button>
                                    <button @click="rateFaq(faq.id, 'unhelpful')"
                                        class="flex items-center space-x-1 text-red-600 hover:text-red-700 transition-colors duration-200">
                                        <HandThumbDownIcon class="h-6 w-6" />
                                        <span>{{ faq.dislikes }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center space-x-4">
                    <button @click="prevPage" :disabled="currentPage === 1"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50">Précédent</button>
                    <span>Page {{ currentPage }} sur {{ totalPages }}</span>
                    <button @click="nextPage" :disabled="currentPage === totalPages"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50">Suivant</button>
                </div>

                <!-- Message si aucun résultat avec illustration -->
                <div v-if="filteredFaqs.length === 0" class="mt-12 text-center bg-white p-8 rounded-lg shadow-md">
                    <ExclamationTriangleIcon class="h-24 w-24 text-yellow-400 mx-auto mb-4" />
                    <p class="text-2xl text-gray-900 font-semibold mb-2">
                        Oups ! Nous n'avons pas trouvé de réponse à votre
                        question.
                    </p>
                    <p class="text-xl text-gray-600">
                        Essayez de reformuler votre recherche ou contactez notre
                        équipe d'assistance.
                    </p>
                </div>

                <!-- Section de contact interactive -->
                <div class="mt-16 bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">
                        Besoin d'aide supplémentaire ?
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div @click="showEmailForm"
                            class="flex flex-col items-center space-y-4 p-6 bg-blue-100 rounded-lg cursor-pointer hover:bg-blue-200 transition-all duration-300">
                            <EnvelopeIcon class="h-12 w-12 text-blue-600" />
                            <h3 class="font-semibold text-blue-900 text-lg">
                                Email
                            </h3>
                            <p class="text-blue-700 text-center">
                                Envoyez-nous un email, nous répondrons sous 24h
                            </p>
                        </div>
                        <div @click="showCallbackForm"
                            class="flex flex-col items-center space-y-4 p-6 bg-green-100 rounded-lg cursor-pointer hover:bg-green-200 transition-all duration-300">
                            <PhoneIcon class="h-12 w-12 text-green-600" />
                            <h3 class="font-semibold text-green-900 text-lg">
                                Rappel téléphonique
                            </h3>
                            <p class="text-green-700 text-center">
                                Demandez à être rappelé par notre équipe
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bouton de retour en haut -->
                <button @click="scrollToTop"
                    class="fixed bottom-8 right-8 bg-indigo-600 p-3 rounded-full text-white hover:bg-indigo-700 transition-all duration-300 shadow-lg">
                    <ChevronUpIcon class="h-6 w-6" />
                </button>
            </div>
        </div>

        <!-- Formulaire d'email -->
        <div v-if="isEmailFormVisible"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Envoyez-nous un email</h3>
                <form @submit.prevent="submitEmailForm">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" id="name" v-model="emailForm.name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" v-model="emailForm.email" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea id="message" v-model="emailForm.message" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="isEmailFormVisible = false"
                            class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Formulaire de rappel -->
        <div v-if="isCallbackFormVisible"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    Demande de rappel
                </h3>
                <form @submit.prevent="submitCallbackForm">
                    <!-- Champs du formulaire -->
                    <div class="mb-4">
                        <label for="callbackName" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" id="callbackName" v-model="callbackForm.name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input type="tel" id="phone" v-model="callbackForm.phone" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div class="mb-4">
                        <label for="preferredTime" class="block text-sm font-medium text-gray-700">Heure préférée pour
                            le rappel</label>
                        <select id="preferredTime" v-model="callbackForm.preferredTime" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="morning">Matin (9h - 12h)</option>
                            <option value="afternoon">
                                Après-midi (14h - 17h)
                            </option>
                            <option value="evening">Soir (17h - 19h)</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="isCallbackFormVisible = false"
                            class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Demander un rappel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de succès -->
        <Modal :show="isSuccessModalVisible" @close="closeModals">
            <div class="p-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <CheckCircleIcon class="h-6 w-6 text-green-600" />
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Succès
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            {{ successMessage }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                        @click="closeModals">
                        Fermer
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Modal d'erreur -->
        <Modal :show="isErrorModalVisible" @close="closeModals">
            <div class="p-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <XCircleIcon class="h-6 w-6 text-red-600" />
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Erreur
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            {{ errorMessage }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm"
                        @click="closeModals">
                        Fermer
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Components/Modal.vue";
import {
    UserCircleIcon,
    HomeIcon,
    CurrencyDollarIcon,
    DocumentTextIcon,
    CogIcon,
    EnvelopeIcon,
    PhoneIcon,
    MagnifyingGlassIcon,
    ChevronUpIcon,
    ExclamationTriangleIcon,
    ChevronDownIcon,
    HandThumbUpIcon,
    HandThumbDownIcon,
    CheckCircleIcon,
    XCircleIcon,
    HomeModernIcon,
} from "@heroicons/vue/24/outline";

// Questions/réponses
const faqs = ref([
    {
        id: 1,
        category: "Locataires",
        question: "Comment mettre à jour les informations d'un locataire ?",
        answer: "Pour mettre à jour les informations d'un locataire :\n1. Accédez à la section 'Locataires' du tableau de bord\n2. Recherchez le locataire que vous souhaitez modifier\n3. Cliquez sur le bouton 'Modifier' à côté du locataire\n4. Mettez à jour les informations nécessaires\n5. Cliquez sur 'Enregistrer' pour appliquer les modifications",
        likes: 0,
        dislikes: 0
    },
    {
        id: 2,
        category: "Locataires",
        question: "Comment archiver un locataire ?",
        answer: "Pour archiver un locataire :\n1. Allez dans la section 'Locataires' du tableau de bord\n2. Recherchez le locataire à archiver\n3. Cliquez sur le bouton 'Archiver'\n4. Confirmez l'action dans la boîte de dialogue qui s'affiche\nLe locataire sera déplacé vers les archives et ne sera plus actif",
        likes: 0,
        dislikes: 0
    },
    {
        id: 3,
        category: "Bailleurs",
        question: "Comment ajouter un nouveau bailleur ?",
        answer: "Pour ajouter un bailleur :\n1. Accédez à la section 'Bailleurs' du tableau de bord\n2. Cliquez sur 'Ajouter un bailleur'\n3. Remplissez les informations requises, telles que le nom, les coordonnées et les propriétés du bailleur\n4. Cliquez sur 'Enregistrer' pour créer le bailleur",
        likes: 0,
        dislikes: 0
    },
    {
        id: 4,
        category: "Bailleurs",
        question:
            "Comment modifier le pourcentage de commission d'un bailleur ?",
        answer: "Pour modifier le pourcentage de commission :\n1. Allez dans la section 'Bailleurs' du tableau de bord\n2. Recherchez le bailleur concerné\n3. Cliquez sur 'Modifier'\n4. Ajustez le pourcentage de commission dans les paramètres du bailleur\n5. Enregistrez les modifications",
        likes: 0,
        dislikes: 0
    },
    {
        id: 5,
        category: "Facturation",
        question: "Comment envoyer une facture à un locataire ?",
        answer: "Pour envoyer une facture :\n1. Accédez à la section 'Factures' du tableau de bord\n2. Sélectionnez la facture que vous souhaitez envoyer\n3. Cliquez sur 'Envoyer par email'\n4. Vérifiez les informations du locataire et ajoutez un message personnalisé si nécessaire\n5. Cliquez sur 'Envoyer' pour transmettre la facture",
        likes: 0,
        dislikes: 0
    },
    {
        id: 6,
        category: "Gestion des données",
        question: "Comment sauvegarder les données de l'application ?",
        answer: "Pour sauvegarder les données de l'application :\n1. Allez dans la section 'Paramètres' du tableau de bord\n2. Sélectionnez 'Sauvegarde des données'\n3. Choisissez le type de sauvegarde (complète ou partielle)\n4. Cliquez sur 'Créer une sauvegarde'\n5. Téléchargez la sauvegarde une fois le processus terminé",
        likes: 0,
        dislikes: 0
    },
    {
        id: 7,
        category: "Paramètres",
        question: "Comment changer le logo de l'entreprise ?",
        answer: "Pour changer le logo de l'entreprise :\n1. Accédez à la section 'Paramètres' du tableau de bord\n2. Cliquez sur 'Informations de l'entreprise'\n3. Cliquez sur 'Changer le logo'\n4. Téléchargez une nouvelle image à partir de votre appareil\n5. Enregistrez les modifications pour mettre à jour le logo",
        likes: 0,
        dislikes: 0
    },
    {
        id: 8,
        category: "Paramètres",
        question: "Comment gérer les utilisateurs de l'application ?",
        answer: "Pour gérer les utilisateurs :\n1. Allez dans la section 'Paramètres' du tableau de bord\n2. Sélectionnez 'Gestion des utilisateurs'\n3. Vous pouvez ajouter, modifier ou supprimer des utilisateurs\n4. Pour chaque utilisateur, définissez les rôles et permissions appropriés\n5. Cliquez sur 'Enregistrer' pour appliquer les changements",
        likes: 0,
        dislikes: 0
    },
]);

// État de la recherche et des catégories
const searchQuery = ref("");
const selectedCategory = ref("Tous");
const openedFaq = ref(null);

// État des formulaires et modales
const isEmailFormVisible = ref(false);
const isCallbackFormVisible = ref(false);
const isSuccessModalVisible = ref(false);
const isErrorModalVisible = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const callbackForm = useForm({
    name: '',
    phone: '',
    preferredTime: ''
});

const emailForm = useForm({
    name: '',
    email: '',
    message: ''
});

const showEmailForm = () => {
    isEmailFormVisible.value = true;
};

// Suggestions de recherche
const searchSuggestions = ref([]);

// Catégories avec icônes
const categories = computed(() => [
    { name: "Tous", icon: UserCircleIcon },
    { name: "Locataires", icon: HomeIcon },
    { name: "Bailleurs", icon: HomeModernIcon },
    { name: "Facturation", icon: CurrencyDollarIcon },
    { name: "Gestion des données", icon: DocumentTextIcon },
    { name: "Paramètres", icon: CogIcon },
]);

// Filtrage des FAQs
const filteredFaqs = computed(() => {
    let filtered = faqs.value;

    if (selectedCategory.value !== "Tous") {
        filtered = filtered.filter(
            (faq) => faq.category === selectedCategory.value
        );
    }

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (faq) =>
                faq.question.toLowerCase().includes(query) ||
                faq.answer.toLowerCase().includes(query)
        );
    }

    return filtered;
});

// Méthodes
const toggleFaq = (id) => {
    openedFaq.value = openedFaq.value === id ? null : id;
};

const rateFaq = (id, rating) => {
    const faq = faqs.value.find(f => f.id === id);
    if (faq) {
        if (rating === 'helpful') {
            faq.likes++;
        } else {
            faq.dislikes++;
        }
    }
    console.log(`FAQ ${id} rated as ${rating}`);
    // Ici, vous pouvez ajouter une logique pour envoyer cette évaluation à votre backend
};

const selectCategory = (category) => {
    selectedCategory.value = category;
};

// Méthode pour soumettre le formulaire d'email
const submitEmailForm = () => {
    emailForm.post(route('help.send-email'), {
        preserveScroll: true,
        onSuccess: () => {
            emailForm.reset();
            isEmailFormVisible.value = false;
            successMessage.value = 'Votre email a été envoyé avec succès.';
            isSuccessModalVisible.value = true;
        },
        onError: () => {
            errorMessage.value = 'Une erreur s\'est produite lors de l\'envoi de votre email.';
            isErrorModalVisible.value = true;
        }
    });
};

// Méthode pour soumettre le formulaire de rappel
const submitCallbackForm = () => {
    callbackForm.post(route('help.request-callback'), {
        preserveScroll: true,
        onSuccess: () => {
            callbackForm.reset();
            isCallbackFormVisible.value = false;
            successMessage.value = 'Votre demande de rappel a été envoyée avec succès.';
            isSuccessModalVisible.value = true;
        },
        onError: () => {
            errorMessage.value = 'Une erreur s\'est produite lors de l\'envoi de votre demande de rappel.';
            isErrorModalVisible.value = true;
        }
    });
};

// Méthode pour fermer les modales
const closeModals = () => {
    isSuccessModalVisible.value = false;
    isErrorModalVisible.value = false;
};

const showCallbackForm = () => {
    isCallbackFormVisible.value = true;
};

const updateSearchSuggestions = () => {
    if (searchQuery.value.length > 2) {
        searchSuggestions.value = faqs.value
            .filter((faq) =>
                faq.question
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase())
            )
            .slice(0, 5)
            .map((faq) => faq.question);
    } else {
        searchSuggestions.value = [];
    }
};

const selectSuggestion = (suggestion) => {
    searchQuery.value = suggestion;
    searchSuggestions.value = [];
};

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
};

// Hooks du cycle de vie
onMounted(() => {
    // Vous pouvez ajouter ici des actions à effectuer lors du montage du composant
    // Par exemple, charger les FAQs depuis une API
    console.log("Composant Centre d'Aide monté");
});

const itemsPerPage = 5;
const currentPage = ref(1);

const paginatedFaqs = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredFaqs.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredFaqs.value.length / itemsPerPage));

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

</script>
<style scoped>
/* Animations pour les transitions de liste */
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

/* Animation pour le fade in/out des formulaires modaux */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Personnalisation de la barre de défilement */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Effet de survol pour les boutons de catégorie */
.category-button {
    transition: all 0.3s ease;
}

.category-button:hover:not(.active) {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Style pour le bouton de retour en haut */
.scroll-to-top {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.scroll-to-top.visible {
    opacity: 1;
    visibility: visible;
}

/* Effet de pulse pour le bouton de chat en direct */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7);
    }

    70% {
        box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
    }
}

/* Style pour les suggestions de recherche */
.search-suggestions {
    max-height: 200px;
    overflow-y: auto;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.search-suggestion-item {
    transition: background-color 0.2s ease;
}

.search-suggestion-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Effet de focus amélioré pour les champs de formulaire */
input:focus,
textarea:focus,
select:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
}

/* Style pour les boutons d'évaluation des FAQs */
.faq-rating-button {
    transition: transform 0.2s ease, color 0.2s ease;
}

.faq-rating-button:hover {
    transform: scale(1.1);
}

.faq-rating-button.helpful:hover {
    color: #34d399;
}

.faq-rating-button.unhelpful:hover {
    color: #f87171;
}

/* Animation pour l'ouverture/fermeture des FAQs */
.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.faq-answer.open {
    max-height: 1000px;
    /* Ajustez cette valeur selon vos besoins */
}
</style>
