<template>
    <div>
        <Head :title="title" />
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white shadow fixed w-full z-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-9 w-auto text-indigo-600" />
                                </Link>
                            </div>
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('landlords.create')" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-indigo-700 hover:border-indigo-300 focus:outline-none focus:text-indigo-700 focus:border-indigo-700 transition duration-150 ease-in-out">
                                    <i class="bi bi-building me-2"></i>
                                    Ajouter bailleur
                                </NavLink>
                                <NavLink :href="route('tenants.create')" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-indigo-700 hover:border-indigo-300 focus:outline-none focus:text-indigo-700 focus:border-indigo-700 transition duration-150 ease-in-out">
                                    <i class="bi bi-people me-2"></i>
                                    Ajouter locataire
                                </NavLink>
                                <NavLink :href="route('properties.create')" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-indigo-700 hover:border-indigo-300 focus:outline-none focus:text-indigo-700 focus:border-indigo-700 transition duration-150 ease-in-out">
                                    <i class="bi bi-house me-2"></i>
                                    Ajouter logement
                                </NavLink>
                                <NavLink :href="route('contracts.create')" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-indigo-700 hover:border-indigo-300 focus:outline-none focus:text-indigo-700 focus:border-indigo-700 transition duration-150 ease-in-out">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    Nouveau dossier
                                </NavLink>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <!-- Search Bar -->
                            <div class="flex-1 px-2 lg:ml-6">
                                <div class="max-w-lg w-full lg:max-w-xs">
                                    <label for="search" class="sr-only">Rechercher</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="bi bi-search text-gray-400"></i>
                                        </div>
                                        <input id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Rechercher" type="search">
                                    </div>
                                </div>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-indigo-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                <i class="bi bi-person"></i>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div v-if="$page.props.jetstream.managesProfilePhotos" class="block px-4 py-2 text-xs text-gray-400">
                                            {{ $page.props.auth.user.name }}
                                        </div>
                                        <div v-else class="block px-4 py-2 text-xs text-gray-400">
                                            Gérer le compte
                                        </div>

                                        <DropdownLink :href="route('profile.show')" class="hover:bg-indigo-600 hover:text-white">
                                            Profil
                                        </DropdownLink>

                                        <div class="border-t border-gray-100" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button" class="hover:bg-indigo-600 hover:text-white">
                                                Déconnexion
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="showingNavigationDropdown = !showingNavigationDropdown">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-700 hover:bg-indigo-50 focus:outline-none focus:text-indigo-700 focus:bg-indigo-50 transition duration-150 ease-in-out">
                            <i class="bi bi-building me-2"></i>
                            <span :class="{ 'hidden': isSidebarCollapsed }">Ajouter bailleur</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-700 hover:bg-indigo-50 focus:outline-none focus:text-indigo-700 focus:bg-indigo-50 transition duration-150 ease-in-out">
                            <i class="bi bi-people me-2"></i>
                            <span :class="{ 'hidden': isSidebarCollapsed }">Ajouter locataire</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-700 hover:bg-indigo-50 focus:outline-none focus:text-indigo-700 focus:bg-indigo-50 transition duration-150 ease-in-out">
                            <i class="bi bi-house me-2"></i>
                            <span :class="{ 'hidden': isSidebarCollapsed }">Ajouter logement</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-700 hover:bg-indigo-50 focus:outline-none focus:text-indigo-700 focus:bg-indigo-50 transition duration-150 ease-in-out">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            <span :class="{ 'hidden': isSidebarCollapsed }">Nouveau dossier</span>
                        </ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <div class="flex pt-16">
                <Sidebar
                    class="fixed left-0 top-16 z-10"
                    :is-collapsed="isSidebarCollapsed"
                    @toggle="toggleSidebar"
                />
                <main
                    class="flex-1 transition-all duration-300"
                    :class="{
                        'ml-52': !isSidebarCollapsed,
                        'ml-14': isSidebarCollapsed
                    }"
                >
                    <div class="p-6">
                        <slot></slot>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, provide } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Sidebar from '@/Components/Sidebar.vue';
import 'bootstrap-icons/font/bootstrap-icons.css';

defineProps({
    title: String,
});

const isSidebarCollapsed = ref(false);

provide('isSidebarCollapsed', isSidebarCollapsed);

const showingNavigationDropdown = ref(false);

const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

const logout = () => {
    router.post(route('logout'));
};

</script>
