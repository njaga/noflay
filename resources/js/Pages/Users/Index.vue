<template>
    <AppLayout title="Utilisateurs">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header with title and add button -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0">
                        <i class="fas fa-users mr-2 text-indigo-600"></i>Liste des Utilisateurs
                    </h1>
                    <div v-if="canManageUsers">
                        <Link :href="route('users.create')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter un utilisateur
                        </Link>

                        <!-- Bouton téléchargement -->
                        <button @click="handleDownloadPDF"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg ml-4">
                            <i class="fas fa-download mr-2"></i>Télécharger PDF
                        </button>

                    </div>
                </div>

                <!-- Search, filter, and view options -->
                <div class="mb-6 bg-white p-4 rounded-lg shadow-md">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full md:w-1/3 mb-4 md:mb-0">
                            <div class="relative">
                                <input type="text" placeholder="Rechercher un utilisateur..."
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:border-indigo-500"
                                    v-model="search">
                                <div class="absolute left-3 top-2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-auto flex space-x-2">
                            <select v-model="sortBy"
                                class="rounded-lg border focus:outline-none focus:border-indigo-500 px-6 py-2">
                                <option value="name">Trier par nom</option>
                                <option value="role">Trier par rôle</option>
                            </select>
                            <button @click="toggleSortOrder"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-150 ease-in-out">
                                <i
                                    :class="['fas', sortOrder === 'asc' ? 'fa-sort-alpha-down' : 'fa-sort-alpha-up']"></i>
                            </button>
                            <button @click="toggleView"
                                class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition duration-150 ease-in-out">
                                <i :class="['fas', viewMode === 'grid' ? 'fa-list' : 'fa-th-large']"></i>
                            </button>
                            <button @click="toggleActiveFilter"
                                :class="['px-4 py-2 rounded-lg transition duration-150 ease-in-out', showInactiveUsers ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200']">
                                {{ showInactiveUsers ? 'Masquer inactifs' : 'Afficher inactifs' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Users list/grid -->
                <div v-if="!paginatedUsers.length"
                    class="text-center text-gray-500 py-10 bg-white rounded-lg shadow-md">
                    <i class="fas fa-info-circle text-4xl mb-4 text-indigo-500"></i>
                    <p>Aucun utilisateur trouvé</p>
                </div>

                <div v-else :class="{ 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6': viewMode === 'grid' }">
                    <template v-if="viewMode === 'grid'">
                        <Card v-for="user in paginatedUsers" :key="user.id" :title="user.name" :email="user.email"
                            :editUrl="route('users.edit', user.id)" :viewUrl="route('users.show', user.id)"
                            :isActive="user.is_active" :roles="getUserRoles(user)"
                            :canToggleStatus="canToggleUserStatus(user)" @delete="openDeleteModal(user)"
                            @toggleStatus="toggleUserStatus(user)" />
                    </template>
                    <div v-else class="bg-white shadow-xl rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rôle
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th v-if="canManageUsers"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in paginatedUsers" :key="user.id"
                                    class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" :src="user.profile_photo_url"
                                                    :alt="user.name">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ user.email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ getUserRoles(user) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800', 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full']">
                                            {{ user.is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td v-if="canManageUsers"
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('users.show', user.id)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('users.edit', user.id)"
                                            class="text-blue-600 hover:text-blue-900 mr-2">
                                        <i class="fas fa-edit"></i>
                                        </Link>
                                        <button @click="openDeleteModal(user)"
                                            class="text-red-600 hover:text-red-900 mr-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button v-if="canToggleUserStatus(user)" @click="toggleUserStatus(user)"
                                            :class="[user.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900']">
                                            <i :class="['fas', user.is_active ? 'fa-toggle-on' : 'fa-toggle-off']"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="totalPages > 1" class="mt-6">
                    <div class="flex justify-between items-center">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50">
                            Précédent
                        </button>
                        <span>Page {{ currentPage }} sur {{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50">
                            Suivant
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete confirmation modal -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <i class="fas fa-exclamation-triangle text-5xl text-yellow-500 animate-bounce"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Êtes-vous sûr de vouloir supprimer l'utilisateur {{ userToDelete?.name }} ? Cette action est
                    irréversible.
                </p>
                <div class="mt-6 flex justify-center space-x-3">
                    <button @click="closeDeleteModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Annuler
                    </button>
                    <button @click="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">
                        Supprimer
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Status update modal -->
        <Modal :show="showStatusModal" @close="closeStatusModal">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <i
                        :class="['fas', 'text-5xl', 'animate-pulse', userStatusUpdated?.is_active ? 'fa-check-circle text-indigo-500' : 'fa-times-circle text-red-500']"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mise à jour du statut</h3>
                <p class="text-sm text-gray-500 mb-4">
                    L'utilisateur {{ userStatusUpdated?.name }} a été {{ userStatusUpdated?.is_active ? 'activé' :
                        'désactivé'
                    }} avec succès.
                </p>
                <div class="mt-6 flex justify-center">
                    <button @click="closeStatusModal"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                        OK
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Delete success modal -->
        <Modal :show="showDeleteSuccessModal" @close="closeDeleteSuccessModal">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <i class="fas fa-trash-alt text-5xl text-indigo-500 animate-fade-in"></i>
                </div>
                <h3 class="text-lg font-medium text-indigo-900 mb-4">Utilisateur supprimé avec succès</h3>
                <p class="text-sm text-gray-500 mb-4">
                    L'utilisateur a été supprimé avec succès de la liste.
                </p>
                <div class="mt-6 flex justify-center">
                    <button @click="closeDeleteSuccessModal"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                        OK
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Delete error modal -->
        <Modal :show="showDeleteErrorModal" @close="closeDeleteErrorModal">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <i class="fas fa-exclamation-circle text-5xl text-red-500 animate-shake"></i>
                </div>
                <h3 class="text-lg font-medium text-red-900 mb-4">Erreur lors de la suppression</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Une erreur s'est produite lors de la suppression de l'utilisateur. Veuillez réessayer plus tard.
                </p>
                <div class="mt-6 flex justify-center">
                    <button @click="closeDeleteErrorModal"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">
                        OK
                    </button>
                </div>
            </div>
        </Modal>

        <!-- User deletion modal -->
        <Modal :show="showUserDeletionModal" @close="closeUserDeletionModal">
            <div class="p-6 text-center">
                <div class="mb-5">
                    <i class="fas fa-user-minus text-5xl text-red-500 animate-fade-out"></i>
                </div>
                <h3 class="text-lg font-medium text-red-900 mb-4">Suppression d'utilisateur</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Vous êtes sur le point de supprimer l'utilisateur {{ userToDelete?.name }}. Cette action est
                    définitive et
                    ne peut être annulée.
                </p>
                <div class="mt-6 flex justify-center space-x-3">
                    <button @click="closeUserDeletionModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Annuler
                    </button>
                    <button @click="proceedWithDeletion"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">
                        Confirmer la suppression
                    </button>
                </div>
            </div>
        </Modal>

    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Modal from '@/Components/Modal.vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

const props = defineProps({
    users: Array,
    auth: Object,
});

const users = ref(props.users.map(user => ({
    ...user,
    roles: user.roles || []
})));
const search = ref('');
const sortBy = ref('name');
const sortOrder = ref('asc');
const viewMode = ref('list');
const showDeleteModal = ref(false);
const userToDelete = ref(null);
const showInactiveUsers = ref(false);
const showStatusModal = ref(false);
const userStatusUpdated = ref(null);
const currentPage = ref(1);
const itemsPerPage = 10;
const showDeleteSuccessModal = ref(false);
const showDeleteErrorModal = ref(false);
const showUserDeletionModal = ref(false);

const filteredUsers = computed(() => {
    let filtered = users.value.filter(user =>
        (user.name.toLowerCase().includes(search.value.toLowerCase()) ||
            getUserRoles(user).toLowerCase().includes(search.value.toLowerCase())) &&
        (showInactiveUsers.value || user.is_active)
    );

    filtered.sort((a, b) => {
        let modifier = sortOrder.value === 'asc' ? 1 : -1;
        if (sortBy.value === 'name') {
            return modifier * a.name.localeCompare(b.name);
        } else {
            return modifier * getUserRoles(a).localeCompare(getUserRoles(b));
        }
    });

    return filtered;
});

const totalPages = computed(() => Math.ceil(filteredUsers.value.length / itemsPerPage));

const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredUsers.value.slice(start, end);
});

const toggleView = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};

const toggleSortOrder = () => {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
};

const toggleActiveFilter = () => {
    showInactiveUsers.value = !showInactiveUsers.value;
    currentPage.value = 1;
};

const openDeleteModal = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};

const confirmDelete = async () => {
    if (userToDelete.value) {
        try {
            await router.delete(route('users.destroy', userToDelete.value.id), {
                preserveState: true,
                preserveScroll: true,
            });
            users.value = users.value.filter(user => user.id !== userToDelete.value.id);
            closeDeleteModal();
            showDeleteSuccessModal.value = true;
        } catch (error) {
            console.error('Error deleting user:', error);
            showDeleteErrorModal.value = true;
        }
    }
};

const closeDeleteSuccessModal = () => {
    showDeleteSuccessModal.value = false;
};

const closeDeleteErrorModal = () => {
    showDeleteErrorModal.value = false;
};

const closeUserDeletionModal = () => {
    showUserDeletionModal.value = false;
};

const getUserRoles = (user) => {
    return user.roles.map(role => getRoleLabel(role.name)).join(', ');
};

const getRoleLabel = (roleName) => {
    switch (roleName) {
        case 'super_admin':
            return 'Super Administrateur';
        case 'admin_entreprise':
            return 'Administrateur';
        case 'user_entreprise':
            return 'Utilisateur';
        case 'bailleur':
            return 'Bailleur';
        case 'locataire':
            return 'Locataire';
        default:
            return roleName;
    }
};

const canManageUsers = computed(() => {
    return props.auth?.user?.roles?.some(role => ['super_admin', 'admin_entreprise'].includes(role.name)) ?? false;
});

const canToggleUserStatus = (user) => {
    return (props.auth?.user?.roles?.some(role => ['super_admin', 'admin_entreprise'].includes(role.name)) ?? false) &&
        user.id !== props.auth?.user?.id && !user.roles?.some(role => role.name === 'super_admin');
};


const toggleUserStatus = async (user) => {
    try {
        const response = await axios.put(route('users.toggle-status', user.id));
        const updatedUser = users.value.find(u => u.id === user.id);
        if (updatedUser) {
            updatedUser.is_active = !updatedUser.is_active;
            userStatusUpdated.value = updatedUser;
            showStatusModal.value = true;
        }
    } catch (error) {
        console.error('Error updating user status:', error);
    }
};

const closeStatusModal = () => {
    showStatusModal.value = false;
    userStatusUpdated.value = null;
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const handleDownloadPDF = () => {
    const doc = new jsPDF();

    // Définition des couleurs
    const primaryColor = [41, 128, 185];
    const secondaryColor = [52, 152, 219];
    const accentColor = [231, 76, 60];

    // Fonction pour ajouter un fond stylisé
    const addStylishBackground = () => {
        doc.setFillColor(...secondaryColor);
        doc.rect(0, 0, doc.internal.pageSize.width, 40, 'F');
        doc.setFillColor(...primaryColor);
        doc.rect(0, 40, doc.internal.pageSize.width, 20, 'F');
    };

    // En-tête stylisé
    const header = () => {
        addStylishBackground();

        // Logo et titre
        doc.setFontSize(24);
        doc.setTextColor(255, 255, 255);
        doc.text('Noflay', 20, 25);

        doc.setFontSize(14);
        doc.text('Application de gestion locative', 20, 35);

        // Informations de contact
        doc.setFontSize(10);
        doc.setTextColor(255, 255, 255);
        doc.text('www.ndiagandiaye.com | +221 78 163 34 19 | Saly Portudal, Mbour', doc.internal.pageSize.width / 2, 55, { align: 'center' });
    };

    // Pied de page stylisé
    const footer = (data) => {
        const pageHeight = doc.internal.pageSize.height;
        doc.setFillColor(...primaryColor);
        doc.rect(0, pageHeight - 20, doc.internal.pageSize.width, 20, 'F');

        doc.setTextColor(255, 255, 255);
        doc.setFontSize(10);
        doc.text(`Page ${data.pageCount}`, doc.internal.pageSize.width - 20, pageHeight - 10, { align: 'right' });

        const today = new Date().toLocaleDateString();
        doc.text(`Généré le ${today}`, 20, pageHeight - 10);
    };

    // Configuration de la table
    doc.autoTable({
        startY: 80,
        head: [['Nom', 'Rôle', 'Statut']],
        body: filteredUsers.value.map(user => [
            user.name,
            getUserRoles(user),
            user.is_active ? 'Actif' : 'Inactif'
        ]),
        theme: 'grid',
        headStyles: {
            fillColor: primaryColor,
            textColor: [255, 255, 255],
            fontStyle: 'bold'
        },
        alternateRowStyles: {
            fillColor: [240, 240, 240]
        },
        styles: {
            cellPadding: 5,
            fontSize: 10,
            valign: 'middle'
        },
        columnStyles: {
            0: { cellWidth: 'auto' },
            1: { cellWidth: 'auto' },
            2: { cellWidth: 40, halign: 'center' }
        },
        didDrawPage: (data) => {
            header();
            footer(data);
        },
        margin: { top: 70 },
    });

    // Ajout d'un résumé stylisé
    const pageWidth = doc.internal.pageSize.width;
    const pageHeight = doc.internal.pageSize.height;
    doc.setFillColor(...secondaryColor);
    doc.roundedRect(20, pageHeight - 60, pageWidth - 40, 30, 3, 3, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(12);
    doc.text(`Total des utilisateurs : ${filteredUsers.value.length}`, 30, pageHeight - 45);
    doc.text(`Utilisateurs actifs : ${filteredUsers.value.filter(u => u.is_active).length}`, 30, pageHeight - 35);

    doc.save('utilisateurs_noflay.pdf');
};

</script>

<style>
@keyframes shake {

    0%,
    100% {
        transform: translateX(0);
    }

    25% {
        transform: translateX(-5px);
    }

    75% {
        transform: translateX(5px);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes fade-out {
    from {
        opacity: 1;
    }

    to {
        opacity: 0;
    }
}

.animate-shake {
    animation: shake 0.5s ease-in-out infinite;
}

.animate-fade-in {
    animation: fade-in 1s ease-in-out;
}

.animate-fade-out {
    animation: fade-out 1s ease-in-out;
}
</style>
