<!-- Sidebar.vue -->
<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import SidebarItem from "./SidebarItem.vue";
import SidebarDropdown from "./SidebarDropdown.vue";

const props = defineProps({
    isCollapsed: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["toggle"]);

const isMobile = ref(false);
const userRole = computed(() => usePage().props.auth.role[0]);

const checkMobile = () => {
    isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
    checkMobile();
    window.addEventListener("resize", checkMobile);
});

onUnmounted(() => {
    window.removeEventListener("resize", checkMobile);
});

const toggleSidebar = () => {
    emit("toggle");
};

watch(isMobile, (newValue) => {
    if (newValue) {
        emit("toggle", true);
    }
});

const navItems = computed(() => {
    const items = [
        {
            name: "Tableau de bord",
            href: route("dashboard"),
            icon: "bi-house",
            roles: ["super_admin", "admin_entreprise", "user_entreprise", "bailleur", "locataire"],
        },
        {
            name: "Entreprises",
            icon: "bi-briefcase",
            roles: ["super_admin"],
            defaultHref: route("companies.index"),
            children: [
                {
                    name: "Gérer les entreprises",
                    href: route("companies.index"),
                    icon: "bi-buildings",
                },
                {
                    name: "Ajouter une entreprise",
                    href: route("companies.create"),
                    icon: "bi-building-add",
                },
            ],
        },
        {
            name: "Bailleurs",
            icon: "bi-building",
            roles: ["super_admin", "admin_entreprise", "user_entreprise"],
            defaultHref: route("landlords.index"),
            children: [
                {
                    name: "Gérer les bailleurs",
                    href: route("landlords.index"),
                    icon: "bi-people",
                },
                {
                    name: "Versements",
                    href: route("landlord-payouts.index"),
                    icon: "bi-person-plus",
                },
                {
                    name: "Archives",
                    href: route("landlords.archives"),
                    icon: "bi-archive",
                },
                {
                    name: "Ajouter un bailleur",
                    href: route("landlords.create"),
                    icon: "bi-person-plus",
                },
            ],
        },
        {
            name: "Locataires",
            icon: "bi-person-check",
            roles: ["super_admin", "admin_entreprise", "user_entreprise", "bailleur"],
            defaultHref: route("tenants.index"),
            children: [
                {
                    name: "Gérer les locataires",
                    href: route("tenants.index"),
                    icon: "bi-people",
                },
                {
                    name: "Archives",
                    href: route("tenants.archives"),
                    icon: "bi-archive",
                },
                {
                    name: "Ajouter un locataire",
                    href: route("tenants.create"),
                    icon: "bi-person-plus",
                },
            ],
        },
        {
            name: "Propriétés",
            icon: "bi-house-door",
            roles: ["super_admin", "admin_entreprise", "user_entreprise", "bailleur", "locataire"],
            defaultHref: route("properties.index"),
            children: [
                {
                    name: "Liste des logements",
                    href: route("properties.index"),
                    icon: "bi-list",
                },
                {
                    name: "Archives",
                    href: route("properties.archives"),
                    icon: "bi-archive",
                },
                {
                    name: "Ajouter un logement",
                    href: route("properties.create"),
                    icon: "bi-plus-circle",
                },
            ],
        },
        {
            name: "Locations",
            icon: "bi-file-earmark-text",
            roles: ["super_admin", "admin_entreprise", "user_entreprise", "bailleur", "locataire"],
            defaultHref: route("contracts.index"),
            children: [
                {
                    name: "Liste des dossiers",
                    href: route("contracts.index"),
                    icon: "bi-list-ul",
                },
                {
                    name: "Archives",
                    href: route("contracts.archives"),
                    icon: "bi-archive",
                },
                {
                    name: "Nouveau dossier",
                    href: route("contracts.create"),
                    icon: "bi-file-earmark-plus",
                },
            ],
        },
        {
            name: "Finances",
            icon: "bi-cash-coin",
            roles: ["super_admin", "admin_entreprise", "user_entreprise", "bailleur", "locataire"],
            defaultHref: route("finance.index"),
            children: [
                {
                    name: "Vue d'ensemble",
                    href: route("finance.index"),
                    icon: "bi-graph-up",
                },
                {
                    name: "Grand livre de caisse",
                    href: route("transactions.grand-livre"),
                    icon: "bi-bar-chart",
                },
                {
                    name: "Ventilation caisse",
                    href: route("transactions.ventilation"),
                    icon: "bi-plus-slash-minus",
                },
                {
                    name: "Dépenses",
                    href: route("expenses.index"),
                    icon: "bi-cart-check",
                },
                {
                    name: "Mensualités",
                    href: route("payments.index"),
                    icon: "bi-cash-stack",
                },
            ],
        },
        {
            name: "Paramètres",
            href: route("users.index"),
            icon: "bi-gear",
            roles: ["super_admin", "admin_entreprise", "user_entreprise"],
        },
        {
            name: "Aide",
            href: route("help"),
            icon: "bi-question-circle",
            roles: ["super_admin", "admin_entreprise", "user_entreprise", "bailleur", "locataire"],
        },
    ];

    return items.filter(item => item.roles.includes(userRole.value));
});

const singleItems = computed(() => navItems.value.filter((item) => !item.children));
const groupItems = computed(() => navItems.value.filter((item) => item.children));
</script>

<template>
    <div
        class="sidebar bg-indigo-800 text-white h-[calc(100vh-4rem)] transition-all duration-300 fixed left-0 top-16 z-10 overflow-y-auto"
        :class="[isCollapsed ? 'w-14' : 'w-52']"
    >
        <div class="p-4">
            <button
                @click="toggleSidebar"
                class="text-white focus:outline-none"
            >
                <i class="bi bi-list text-2xl"></i>
            </button>
        </div>
        <nav class="mt-2">
            <SidebarItem
                v-for="item in singleItems"
                :key="item.name"
                :href="item.href"
                :icon="item.icon"
                :text="item.name"
                :is-collapsed="isCollapsed"
                :is-mobile="isMobile"
            />

            <SidebarDropdown
                v-for="group in groupItems"
                :key="group.name"
                :icon="group.icon"
                :text="group.name"
                :is-collapsed="isCollapsed"
                :is-mobile="isMobile"
                :default-href="group.defaultHref"
            >
                <SidebarItem
                    v-for="subItem in group.children"
                    :key="subItem.name"
                    :href="subItem.href"
                    :icon="subItem.icon"
                    :text="subItem.name"
                    :is-collapsed="isCollapsed"
                    :is-mobile="isMobile"
                />
            </SidebarDropdown>
        </nav>
    </div>
</template>
