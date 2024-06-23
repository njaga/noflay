<template>
    <AppLayout title="Liste des bailleurs">
        <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header with title and add button -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900">
                        <i class="fas fa-user-tie text-indigo-600 mr-2"></i>Liste des bailleurs
                    </h1>
                    <button @click="goToCreate"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter un bailleur
                    </button>
                </div>

                <!-- Search and filter options -->
                <div class="mb-6 bg-white p-4 rounded-lg shadow-md">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full md:w-1/3 mb-4 md:mb-0">
                            <div class="relative">
                                <input type="text" placeholder="Rechercher un bailleur..."
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
                            </select>
                            <button @click="toggleSortOrder"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-150 ease-in-out">
                                <i
                                    :class="['fas', sortOrder === 'asc' ? 'fa-sort-alpha-down' : 'fa-sort-alpha-up']"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Landlords table -->
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
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
                                    Téléphone
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Adresse
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="landlord in filteredLandlords" :key="landlord.id"
                                class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full"
                                                :src="landlord.avatar || 'https://img.icons8.com/nolan/40/user-default.png'"
                                                :alt="landlord.prenom">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ landlord.prenom }} {{ landlord.nom }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ landlord.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ landlord.telephone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ landlord.adresse }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="goToShow(landlord.id)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="goToEdit(landlord.id)"
                                        class="text-blue-600 hover:text-blue-900 mr-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="confirmDelete(landlord)" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                    Êtes-vous sûr de vouloir supprimer le bailleur {{ landlordToDelete?.prenom }} {{
                    landlordToDelete?.nom }} ? Cette action est irréversible.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="closeDeleteModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Annuler
                    </button>
                    <button @click="deleteLandlord"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150 ease-in-out">
                        Supprimer
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script>
import { defineComponent, ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';

export default defineComponent({
    components: {
        AppLayout,
        Modal,
    },
    setup() {
        const { props } = usePage();
        const landlords = ref(props.landlords);
        const search = ref('');
        const sortBy = ref('name');
        const sortOrder = ref('asc');
        const showDeleteModal = ref(false);
        const landlordToDelete = ref(null);

        const filteredLandlords = computed(() => {
            let filtered = landlords.value.filter(landlord =>
                landlord.prenom.toLowerCase().includes(search.value.toLowerCase()) ||
                landlord.nom.toLowerCase().includes(search.value.toLowerCase()) ||
                landlord.email.toLowerCase().includes(search.value.toLowerCase())
            );

            filtered.sort((a, b) => {
                let modifier = sortOrder.value === 'asc' ? 1 : -1;
                if (sortBy.value === 'name') {
                    return modifier * (`${a.prenom} ${a.nom}`).localeCompare(`${b.prenom} ${b.nom}`);
                } else {
                    return modifier * a.email.localeCompare(b.email);
                }
            });

            return filtered;
        });

        const goToCreate = () => {
            router.visit(route('landlords.create'));
        };

        const goToShow = (id) => {
            router.visit(route('landlords.show', id));
        };

        const goToEdit = (id) => {
            router.visit(route('landlords.edit', id));
        };

        const confirmDelete = (landlord) => {
            landlordToDelete.value = landlord;
            showDeleteModal.value = true;
        };

        const closeDeleteModal = () => {
            showDeleteModal.value = false;
            landlordToDelete.value = null;
        };

        const deleteLandlord = () => {
            if (landlordToDelete.value) {
                router.delete(route('landlords.destroy', landlordToDelete.value.id), {
                    onSuccess: () => {
                        closeDeleteModal();
                        // You might want to refresh the landlords list here
                    },
                });
            }
        };

        const toggleSortOrder = () => {
            sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
        };

        return {
            landlords,
            search,
            sortBy,
            sortOrder,
            filteredLandlords,
            showDeleteModal,
            landlordToDelete,
            goToCreate,
            goToShow,
            goToEdit,
            confirmDelete,
            closeDeleteModal,
            deleteLandlord,
            toggleSortOrder,
        };
    }
});
</script>
