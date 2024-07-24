<template>
    <AppLayout :title="'Résultats de recherche'">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 drop-shadow-xl">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-5xl font-extrabold text-indigo-900 mb-4 animate-fade-in">
                    Résultats de recherche pour "{{ query }}"
                </h1>
                <p class="text-2xl text-indigo-700 mb-8 animate-fade-in-delay">
                    {{ totalResults }} résultats correspondant à votre recherche
                </p>

                <div class="flex justify-between items-center mb-8 animate-fade-in-delay-2">
                    <nav class="flex flex-wrap space-x-4">
                        <button
                            v-for="category in nonEmptyCategories"
                            :key="category.name"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 ease-in-out mb-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            :class="{
                                'bg-indigo-600 text-white hover:bg-indigo-700': selectedCategory === category.name,
                                'bg-white text-indigo-600 hover:bg-indigo-100': selectedCategory !== category.name,
                            }"
                            @click="selectCategory(category.name)"
                        >
                            {{ getCategoryTitle(category.name) }}
                            <span class="ml-1 text-xs font-semibold">{{ category.count }}</span>
                        </button>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <button
                            @click="toggleView"
                            class="px-4 py-2 bg-indigo-100 text-indigo-600 rounded-md hover:bg-indigo-200 transition duration-300 ease-in-out"
                        >
                            <i :class="viewMode === 'grid' ? 'bi bi-list' : 'bi bi-grid'" class="mr-2"></i>
                            {{ viewMode === 'grid' ? 'Vue liste' : 'Vue grille' }}
                        </button>
                    </div>
                </div>

                <div v-if="totalResults === 0" class="text-gray-600 text-center animate-fade-in-delay-3">
                    <i class="bi bi-search text-6xl text-indigo-500"></i>
                    <p class="mt-4 text-xl">
                        Aucun élément ne correspond à votre recherche.
                    </p>
                </div>

                <div v-else class="space-y-16">
                    <template v-for="(categoryData, categoryName) in nonEmptyCategoryResults" :key="categoryName">
                        <div v-if="shouldShowCategory(categoryName)" class="animate-fade-in-up">
                            <h2 class="text-3xl font-semibold text-indigo-900 mb-6">
                                {{ getCategoryTitle(categoryName) }}
                            </h2>
                            <div :class="{'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8': viewMode === 'grid', 'space-y-4': viewMode === 'list'}">
                                <div
                                    v-for="item in categoryData"
                                    :key="item.id"
                                    :class="{'bg-white shadow-lg rounded-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl': viewMode === 'grid', 'bg-white shadow rounded-lg overflow-hidden transition duration-300 hover:shadow-lg': viewMode === 'list'}"
                                >
                                    <div class="p-6">
                                        <h3 class="text-xl font-semibold text-indigo-900 mb-3">
                                            {{ getItemTitle(item, categoryName) }}
                                        </h3>
                                        <div
                                            v-for="(value, key) in getItemDetails(item, categoryName)"
                                            :key="key"
                                            class="text-indigo-700 mb-2"
                                        >
                                            <i :class="getIconClass(key)" class="mr-2"></i>{{ value }}
                                        </div>
                                        <Link
                                            :href="getItemLink(item, categoryName)"
                                            class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-300 ease-in-out"
                                        >
                                            Voir plus
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    query: String,
    users: Array,
    tenants: Array,
    landlords: Array,
    properties: Array,
    expenses: Array,
    payments: Array,
    transactions: Array,
    contracts: Array,
});

const selectedCategory = ref("Tous");
const viewMode = ref("grid");

const totalResults = computed(() => {
    return Object.values(props)
        .filter(Array.isArray)
        .reduce((sum, arr) => sum + arr.length, 0);
});

const nonEmptyCategories = computed(() => {
    const allCategory = { name: "Tous", count: totalResults.value };
    const categories = Object.entries(props)
        .filter(([key, value]) => Array.isArray(value) && value.length > 0)
        .map(([key, value]) => ({
            name: key.charAt(0).toUpperCase() + key.slice(1),
            count: value.length,
        }));
    return [allCategory, ...categories];
});

const nonEmptyCategoryResults = computed(() => {
    return Object.fromEntries(
        Object.entries(props)
            .filter(([key, value]) => Array.isArray(value) && value.length > 0)
            .map(([key, value]) => [key.charAt(0).toUpperCase() + key.slice(1), value])
    );
});

const selectCategory = (category) => {
    selectedCategory.value = category;
};

const toggleView = () => {
    viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};

const shouldShowCategory = (category) => {
    return selectedCategory.value === "Tous" || selectedCategory.value === category;
};

const getCategoryTitle = (category) => {
    const titles = {
        Tous: "Tous",
        Users: "Utilisateurs",
        Tenants: "Locataires",
        Landlords: "Bailleurs",
        Properties: "Propriétés",
        Expenses: "Dépenses",
        Payments: "Paiements de Mensualité",
        Transactions: "Transactions",
        Contracts: "Contrats",
    };
    return titles[category] || category;
};

const getItemTitle = (item, category) => {
    switch (category) {
        case "Users":
            return item.name;
        case "Tenants":
        case "Landlords":
            return `${item.first_name} ${item.last_name}`;
        case "Properties":
            return item.name;
        case "Expenses":
            return item.description;
        case "Payments":
        case "Transactions":
            return `#${item.id}`;
        case "Contracts":
            return item.name;
        default:
            return `Item #${item.id}`;
    }
};

const getItemDetails = (item, category) => {
    switch (category) {
        case "Users":
            return {
                email: item.email,
            };
        case "Tenants":
        case "Landlords":
            return {
                email: item.email,
                phone: item.phone_number || item.phone,
            };
        case "Properties":
            return {
                address: item.address,
            };
        case "Expenses":
        case "Payments":
        case "Transactions":
            return {
                amount: formatCurrency(item.amount),
                date: formatDate(item.date || item.expense_date || item.payment_date),
            };
        case "Contracts":
            return {
                startDate: formatDate(item.start_date),
            };
        default:
            return {};
    }
};

const getItemLink = (item, category) => {
    const routeNames = {
        Users: "users.show",
        Tenants: "tenants.show",
        Landlords: "landlords.show",
        Properties: "properties.show",
        Expenses: "expenses.show",
        Payments: "payments.show",
        Transactions: "transactions.show",
        Contracts: "contracts.show",
    };
    return route(routeNames[category], item.id);
};

const getIconClass = (key) => {
    const iconClasses = {
        email: "bi bi-envelope-fill",
        phone: "bi bi-telephone-fill",
        address: "bi bi-geo-alt-fill",
        amount: "bi bi-currency-dollar",
        date: "bi bi-calendar-date",
        startDate: "bi bi-calendar-check",
    };
    return iconClasses[key] || "bi bi-info-circle";
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "XOF",
    }).format(amount);
};
</script>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

.animate-fade-in-delay {
    animation: fadeIn 0.5s ease-out 0.2s both;
}

.animate-fade-in-delay-2 {
    animation: fadeIn 0.5s ease-out 0.4s both;
}

.animate-fade-in-delay-3 {
    animation: fadeIn 0.5s ease-out 0.6s both;
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out;
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
