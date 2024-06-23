<template>
    <AppLayout title="Utilisateurs">
        <div class="min-h-scree py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header with title and add button -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0">
                        <i class="fas fa-users mr-2 text-indigo-600"></i>Liste des Utilisateurs
                    </h1>
                    <Link :href="route('users.create')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Ajouter un utilisateur
                    </Link>
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
                                <option value="email">Trier par email</option>
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
                        </div>
                    </div>
                </div>

                <!-- Users list/grid -->
                <div v-if="!filteredUsers.length" class="text-center text-gray-500 py-10 bg-white rounded-lg shadow-md">
                    <i class="fas fa-info-circle text-4xl mb-4 text-indigo-500"></i>
                    <p>Aucun utilisateur trouvé</p>
                </div>

                <div v-else :class="{ 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6': viewMode === 'grid' }">
                    <template v-if="viewMode === 'grid'">
                        <Card v-for="user in filteredUsers" :key="user.id" :title="user.name" :email="user.email"
                            :editUrl="route('users.edit', user.id)" :viewUrl="route('users.show', user.id)"
                            :isActive="user.is_active" :role="user.role" :canToggleStatus="canToggleUserStatus(user)"
                            @delete="openDeleteModal(user)" @toggleStatus="toggleUserStatus(user)" />
                    </template>
                    <div v-else class="bg-white shadow-xl rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rôle</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in filteredUsers" :key="user.id"
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ user.email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ getRoleLabel(user.role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800', 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full']">
                                            {{ user.is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
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
                <div class="mt-6">
                    <!-- Add your pagination component here -->
                </div>
            </div>
        </div>

        <!-- Delete confirmation modal -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Êtes-vous sûr de vouloir supprimer l'utilisateur {{ userToDelete?.name }} ? Cette action est
                    irréversible.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
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
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    users: Array,
    auth: Object,
});

const users = ref([...props.users]);
const search = ref('');
const sortBy = ref('name');
const sortOrder = ref('asc');
const viewMode = ref('grid');
const showDeleteModal = ref(false);
const userToDelete = ref(null);

const filteredUsers = computed(() => {
    let filtered = users.value.filter(user =>
        user.name.toLowerCase().includes(search.value.toLowerCase()) ||
        user.email.toLowerCase().includes(search.value.toLowerCase()) ||
        user.role.toLowerCase().includes(search.value.toLowerCase())
    );

    filtered.sort((a, b) => {
        let modifier = sortOrder.value === 'asc' ? 1 : -1;
        if (sortBy.value === 'name') {
            return modifier * a.name.localeCompare(b.name);
        } else if (sortBy.value === 'email') {
            return modifier * a.email.localeCompare(b.email);
        } else {
            return modifier * a.role.localeCompare(b.role);
        }
    });

    return filtered;
});

const toggleView = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};

const toggleSortOrder = () => {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
};

const openDeleteModal = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};

const confirmDelete = () => {
    if (userToDelete.value) {
        router.delete(route('users.destroy', userToDelete.value.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                users.value = users.value.filter(user => user.id !== userToDelete.value.id);
                closeDeleteModal();
            }
        });
    }
};

const canToggleUserStatus = (user) => {
    return props.auth.user.role === 'super_admin' ||
        (props.auth.user.role === 'admin_entreprise' && user.company_id === props.auth.user.company_id);
};

const getRoleLabel = (role) => {
    switch (role) {
        case 'super_admin':
            return 'Super Admin'
        case 'user_entreprise':
            return 'Utilisateur'
        case 'landlord':
            return 'Bailleur'
        case 'tenant':
            return 'Locataire'
        case 'admin_entreprise':
            return 'Admin'
        default:
            return 'N/A'
    }
};

const toggleUserStatus = (user) => {
    router.put(route('users.toggleStatus', user.id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            const updatedUser = users.value.find(u => u.id === user.id);
            if (updatedUser) {
                updatedUser.is_active = !updatedUser.is_active;
            }
        }
    });
};
</script>
