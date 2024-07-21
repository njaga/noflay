<template>
    <AppLayout :title="`${property.name} - Détails de la propriété`">
        <div class="min-h-screen bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Hero Section -->
                <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-12 bg-white">
                    <img :src="coverImage" :alt="property.name" class="w-full h-[50vh] object-cover" />
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-gray-900/70 to-transparent flex flex-col justify-end p-8">
                        <h1 class="text-4xl font-bold text-white mb-4 animate-fade-in-up">
                            {{ property.name }}
                        </h1>
                        <div class="flex items-center space-x-4 animate-fade-in-up" style="animation-delay: 0.2s">
                            <span class="bg-indigo-700 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                {{ property.property_type }}
                            </span>
                            <span :class="[
                                'px-4 py-2 rounded-full text-sm font-semibold',
                                property.available_count > 0
                                    ? 'bg-green-500 text-white'
                                    : 'bg-red-500 text-white',
                            ]">
                                {{
                                    property.available_count > 0
                                        ? "Disponible"
                                        : "Non disponible"
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Description, Details, and Gallery -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Quick Info -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in" style="animation-delay: 0.4s">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <InfoItem icon="fa fa-map-marker-alt" label="Adresse" :value="property.address" />
                                <InfoItem icon="fa fa-home" label="Disponibles" :value="property.available_count" />
                                <InfoItem icon="fa fa-building" label="Entreprise" :value="property.company.name" />
                                <InfoItem icon="fa fa-user" label="Bailleur"
                                    :value="`${property.landlord.first_name} ${property.landlord.last_name}`" />
                                <InfoItem icon="fa fa-dollar-sign" label="Prix de la Propriété"
                                    :value="formatCurrency(rentAmount)" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in" style="animation-delay: 0.6s">
                            <h2 class="text-2xl font-semibold mb-4 text-gray-800">
                                À propos de cette propriété
                            </h2>
                            <p class="text-gray-600 text-base leading-relaxed">
                                {{ property.description }}
                            </p>
                        </div>

                        <!-- Photo Gallery -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in" style="animation-delay: 0.8s">
                            <h2 class="text-2xl font-semibold mb-4 text-gray-800">
                                Galerie photos
                            </h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <div v-for="(photo, index) in parsedPhotos" :key="index"
                                    class="relative group cursor-pointer overflow-hidden rounded-lg"
                                    @click="openLightbox(index)">
                                    <img :src="`/storage/${photo}`" :alt="`Photo ${index + 1}`"
                                        class="w-full h-48 object-cover transition duration-300 ease-in-out transform group-hover:scale-110" />
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 ease-in-out flex items-center justify-center">
                                        <i
                                            class="fas fa-search text-white text-2xl opacity-0 group-hover:opacity-100 transition duration-300 ease-in-out"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Stats, Tenants, and Actions -->
                    <div class="space-y-8">
                        <!-- Property Stats -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in relative"
                            style="animation-delay: 1s">
                            <h2 class="text-2xl font-semibold mb-8 text-gray-800">
                                Statistiques
                            </h2>
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <BarChart :chartData="chartDataCautions" />
                                    </div>
                                    <div class="swiper-slide">
                                        <BarChart :chartData="chartDataLoyers" />
                                    </div>
                                    <div class="swiper-slide">
                                        <BarChart :chartData="chartDataTotalRent" />
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                            </div>
                        </div>

                        <!-- Tenants List -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in" style="animation-delay: 1.2s">
                            <h2 class="text-2xl font-semibold mb-4 text-gray-800">
                                Locataires
                            </h2>
                            <div v-if="
                                property.contracts &&
                                property.contracts.length
                            " class="space-y-4">
                                <div v-for="contract in property.contracts" :key="contract.id"
                                    class="flex items-center p-4 bg-gray-50 rounded-lg transition duration-300 ease-in-out hover:bg-gray-100">
                                    <img :src="`https://ui-avatars.com/api/?name=${contract.tenant.first_name}+${contract.tenant.last_name}&background=4F46E5&color=ffffff`"
                                        :alt="contract.tenant.first_name"
                                        class="w-12 h-12 rounded-full mr-4 shadow-md" />
                                    <Link :href="route(
                                        'tenants.show',
                                        contract.tenant.id
                                    )
                                        "
                                        class="text-base text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                                    {{ contract.tenant.first_name }}
                                    {{ contract.tenant.last_name }}
                                    </Link>
                                </div>
                            </div>
                            <p v-else class="text-gray-500 italic text-center py-4">
                                Aucun locataire pour le moment
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in" style="animation-delay: 1.4s">
                            <h2 class="text-2xl font-semibold mb-4 text-gray-800">
                                Actions
                            </h2>
                            <div class="space-y-4">
                                <Link :href="route('properties.edit', property.id)
                                    "
                                    class="flex items-center justify-center w-full bg-indigo-600 text-white px-4 py-3 rounded-lg font-semibold transition duration-300 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-edit mr-2"></i> Modifier la
                                propriété
                                </Link>
                                <Link :href="route('tenants.create', {
                                    property_id: property.id,
                                })
                                    "
                                    class="flex items-center justify-center w-full bg-green-600 text-white px-4 py-3 rounded-lg font-semibold transition duration-300 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-user-plus mr-2"></i>
                                Ajouter un locataire
                                </Link>

                                <!-- Générer rapport
                                <Link
                                    :href="
                                        route('properties.report', property.id)
                                    "
                                    class="flex items-center justify-center w-full bg-purple-600 text-white px-4 py-3 rounded-lg font-semibold transition duration-300 ease-in-out hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                >
                                    <i class="fas fa-file-alt mr-2"></i> Générer
                                    un rapport
                                </Link>
                                -->

                                <button @click="showDeleteModal = true"
                                    class="flex items-center justify-center w-full bg-red-600 text-white px-4 py-3 rounded-lg font-semibold transition duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <TransitionRoot appear :show="lightboxOpen" as="template">
            <Dialog as="div" @close="closeLightbox" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="min-h-screen px-4 text-center">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                        enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <DialogOverlay class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" />
                    </TransitionChild>
                    <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <div
                            class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                            <img :src="`/storage/${parsedPhotos[currentPhotoIndex]}`" :alt="`Photo de la propriété ${currentPhotoIndex + 1
                                }`" class="w-full h-auto rounded-lg shadow-lg" />
                            <div class="mt-6 flex justify-between items-center">
                                <button @click="previousPhoto"
                                    class="bg-gray-200 hover:bg-gray-300 rounded-full p-3 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-chevron-left text-gray-600"></i>
                                </button>
                                <span class="text-gray-500">
                                    {{ currentPhotoIndex + 1 }} /
                                    {{ parsedPhotos.length }}
                                </span>
                                <button @click="nextPhoto"
                                    class="bg-gray-200 hover:bg-gray-300 rounded-full p-3 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-chevron-right text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <DeleteModal :show="showDeleteModal" :property="property" @close="showDeleteModal = false"
            @confirm="deleteProperty" />
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InfoItem from "@/Components/InfoItem.vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogOverlay,
} from "@headlessui/vue";
import BarChart from "@/Components/BarChart.vue";
import Swiper from "swiper/bundle";
import "swiper/swiper-bundle.css";

const { props } = usePage();
const property = props.property;

const cautionCommission = props.commissionCautions;
const rentCommission = props.commissionLoyers;
const rentAmount = props.rentAmount;
const totalRentAmount = props.totalRentAmount;

const parsedPhotos = computed(() => {
    return property.photos ? JSON.parse(property.photos) : [];
});

const coverImage = computed(() => {
    return parsedPhotos.value.length > 0
        ? `/storage/${parsedPhotos.value[0]}`
        : "/img/default-property.jpg";
});

const lightboxOpen = ref(false);
const currentPhotoIndex = ref(0);
const showDeleteModal = ref(false);

const openLightbox = (index) => {
    currentPhotoIndex.value = index;
    lightboxOpen.value = true;
};

const closeLightbox = () => {
    lightboxOpen.value = false;
};

const previousPhoto = () => {
    currentPhotoIndex.value =
        (currentPhotoIndex.value - 1 + parsedPhotos.value.length) %
        parsedPhotos.value.length;
};

const nextPhoto = () => {
    currentPhotoIndex.value =
        (currentPhotoIndex.value + 1) % parsedPhotos.value.length;
};

const deleteProperty = () => {
    router.delete(route("properties.destroy", property.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            router.get(route("properties.index"));
        },
        onError: () => {
            showDeleteModal.value = false;
        },
    });
};

const chartDataCautions = computed(() => ({
    labels: ["Cautions"],
    datasets: [
        {
            label: "Commissions Cautions",
            data: [cautionCommission],
            backgroundColor: ["#4F46E5"],
        },
    ],
}));

const chartDataLoyers = computed(() => ({
    labels: ["Loyers"],
    datasets: [
        {
            label: "Commissions Loyers",
            data: [rentCommission],
            backgroundColor: ["#10B981"],
        },
    ],
}));

const chartDataTotalRent = computed(() => ({
    labels: ["Total Rent"],
    datasets: [
        {
            label: "Total Rent Amount",
            data: [totalRentAmount],
            backgroundColor: ["#8B5CF6"],
        },
    ],
}));

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

onMounted(() => {
    new Swiper('.swiper-container', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 5000,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
    });
});
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
}

.swiper-container {
    width: 100%;
    height: 300px;
    position: relative;
    padding-bottom: 40px;
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-button-prev,
.swiper-button-next {
    color: #4F46E5;
    width: 30px;
    /* Réduire la largeur */
    height: 30px;
    /* Réduire la hauteur */
    background-color: rgba(255, 255, 255, 0.8);
    /* Fond légèrement transparent */
    border-radius: 50%;
    /* Forme circulaire */
    transition: background-color 0.3s ease;
}

.swiper-button-prev:hover,
.swiper-button-next:hover {
    background-color: rgba(255, 255, 255, 1);
    /* Fond opaque au survol */
}

.swiper-button-prev::after,
.swiper-button-next::after {
    font-size: 14px;
    /* Réduire la taille de l'icône */
    font-weight: bold;
}

.swiper-pagination {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 0;
}

.swiper-pagination-bullet {
    width: 8px;
    height: 8px;
    background-color: #D1D5DB;
    opacity: 1;
    margin: 0 5px;
}

.swiper-pagination-bullet-active {
    background-color: #4F46E5;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
