<!-- Sidebar.vue -->
<template>
    <div class="sidebar bg-indigo-800 text-white h-screen transition-all duration-300 fixed left-0 top-16 z-10"
         :class="[isCollapsed || isMobile ? 'w-14' : 'w-52']">
        <div class="p-4" v-if="!isMobile">
            <button @click="$emit('toggle')" class="text-white focus:outline-none">
                <i class="bi bi-list text-2xl"></i>
            </button>
        </div>
        <nav class="mt-2">
            <SidebarItem :href="route('dashboard')" icon="bi-house" text="Tableau de bord" :is-collapsed="isCollapsed" :is-mobile="isMobile" />

            <SidebarDropdown icon="bi-briefcase" text="Entreprises" :is-collapsed="isCollapsed" :is-mobile="isMobile">
                <SidebarItem :href="route('companies.index')" icon="bi-buildings" text="Gérer les entreprises" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
                <SidebarItem :href="route('companies.create')" icon="bi-building-add" text="Ajouter une entreprise" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
            </SidebarDropdown>

            <SidebarDropdown icon="bi-building" text="Bailleurs" :is-collapsed="isCollapsed" :is-mobile="isMobile">
                <SidebarItem :href="route('landlords.index')" icon="bi-people" text="Gérer les bailleurs" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
                <SidebarItem :href="route('landlord-payouts.index')" icon="bi-person-plus" text="Versements" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
                <SidebarItem :href="route('landlords.create')" icon="bi-person-plus" text="Ajouter un bailleur" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
            </SidebarDropdown>

            <SidebarDropdown icon="bi-person-check" text="Locataires" :is-collapsed="isCollapsed" :is-mobile="isMobile">
                <SidebarItem :href="route('tenants.index')" icon="bi-people" text="Gérer les locataires" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
                <SidebarItem :href="route('tenants.create')" icon="bi-person-plus" text="Ajouter un locataire" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
            </SidebarDropdown>

            <SidebarDropdown icon="bi-house-door" text="Logements" :is-collapsed="isCollapsed" :is-mobile="isMobile">
                <SidebarItem :href="route('properties.index')" icon="bi-list" text="Liste des logements" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
                <SidebarItem :href="route('properties.create')" icon="bi-plus-circle" text="Ajouter un logement" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
            </SidebarDropdown>

            <SidebarDropdown icon="bi-file-earmark-text" text="Locations" :is-collapsed="isCollapsed" :is-mobile="isMobile">
                <SidebarItem :href="route('contracts.index')" icon="bi-list-ul" text="Liste des dossiers" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
                <SidebarItem :href="route('contracts.create')" icon="bi-file-earmark-plus" text="Nouveau dossier" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
            </SidebarDropdown>

            <SidebarDropdown icon="bi-cash-coin" text="Finances" :is-collapsed="isCollapsed" :is-mobile="isMobile">
                <SidebarItem :href="route('finance.index')" icon="bi-graph-up" text="Vue d'ensemble" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
                <SidebarItem :href="route('expenses.index')" icon="bi-cart-check" text="Dépenses" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
                <SidebarItem :href="route('payments.index')" icon="bi bi-cash-stack" text="Mensualités" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
            </SidebarDropdown>

            <SidebarItem :href="route('users.index')" icon="bi-gear" text="Paramètres" :is-collapsed="isCollapsed" :is-mobile="isMobile" />

            <SidebarItem href="route('help')" icon="bi-question-circle" text="Aide" :is-collapsed="isCollapsed" :is-mobile="isMobile" />
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import SidebarItem from './SidebarItem.vue';
import SidebarDropdown from './SidebarDropdown.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    isCollapsed: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['toggle']);

const isMobile = ref(false);

const checkMobile = () => {
    isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile);
});
</script>
