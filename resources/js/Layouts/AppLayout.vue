<template>
    <div>
        <Head :title="title" />
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white shadow fixed w-full z-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark
                                        class="block h-9 w-auto text-indigo-600"
                                    />
                                </Link>
                            </div>
                            <div class="hidden md:flex space-x-8 ml-10">
                                <NavLink
                                    v-for="link in navLinks"
                                    :key="link.route"
                                    :href="route(link.route)"
                                    :active="route().current(link.route)"
                                >
                                    <i :class="link.icon" class="me-2"></i>
                                    {{ link.text }}
                                </NavLink>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="hidden md:block w-36 lg:w-48">
                                <SearchBar />
                            </div>
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-indigo-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.show')"
                                        >
                                            Profil
                                        </DropdownLink>
                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button" class="hover:bg-indigo-600 hover:text-white">
                                                Déconnexion
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                            <div class="flex items-center md:hidden">
                                <button
                                    @click="showMobileMenu = !showMobileMenu"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                >
                                    <svg
                                        class="h-6 w-6"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            :class="{
                                                hidden: showMobileMenu,
                                                'inline-flex': !showMobileMenu,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{
                                                hidden: !showMobileMenu,
                                                'inline-flex': showMobileMenu,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    :class="{ block: showMobileMenu, hidden: !showMobileMenu }"
                    class="md:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            v-for="link in navLinks"
                            :key="link.route"
                            :href="route(link.route)"
                            :active="route().current(link.route)"
                        >
                            <i :class="link.icon" class="me-2"></i>
                            {{ link.text }}
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div
                                v-if="$page.props.auth.user.profile_photo_url"
                                class="h-10 w-10 rounded-full overflow-hidden"
                            >
                                <img
                                    class="h-full w-full object-cover"
                                    :src="
                                        $page.props.auth.user.profile_photo_url
                                    "
                                    :alt="$page.props.auth.user.name"
                                />
                            </div>
                            <div
                                v-else
                                class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold"
                            >
                                {{ userInitials }}
                            </div>
                            <div class="ml-3">
                                <div
                                    class="text-base font-medium text-gray-800"
                                >
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')">
                                Profil
                            </ResponsiveNavLink>
                            <ResponsiveNavLink as="button" @click="logout">
                                Déconnexion
                            </ResponsiveNavLink>
                        </div>
                    </div>
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="px-4">
                            <SearchBar />
                        </div>
                    </div>
                </div>
            </nav>
            <div class="flex pt-16">
                <Sidebar
                    class="fixed left-0 top-16 z-10 h-[calc(100vh-4rem)]"
                    :is-collapsed="isSidebarCollapsed"
                    @toggle="toggleSidebar"
                />
                <main
                    class="flex-1 transition-all duration-300 ml-14 md:ml-16"
                    :class="{
                        'md:ml-56': !isSidebarCollapsed,
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
import { ref, provide, computed } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Sidebar from "@/Components/Sidebar.vue";
import SearchBar from "@/Components/SearchBar.vue";
import "bootstrap-icons/font/bootstrap-icons.css";

defineProps({
    title: String,
});

const isSidebarCollapsed = ref(false);
const showMobileMenu = ref(false);

provide("isSidebarCollapsed", isSidebarCollapsed);

const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

const navLinks = [
    {
        route: "landlords.create",
        icon: "bi bi-building",
        text: "Ajouter bailleur",
    },
    {
        route: "tenants.create",
        icon: "bi bi-people",
        text: "Ajouter locataire",
    },
    {
        route: "properties.create",
        icon: "bi bi-house",
        text: "Ajouter logement",
    },
    {
        route: "contracts.create",
        icon: "bi bi-file-earmark-text",
        text: "Nouveau dossier",
    },
];

const userInitials = computed(() => {
    const name = usePage().props.auth.user.name;
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
});

const logout = () => {
    router.post(route('logout'));
};

const userRole = computed(() => usePage().props.auth.role[0]);
</script>
