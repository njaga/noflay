<template>
    <AppLayout title="Gestion des locataires">
      <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
            <h1 class="text-4xl font-extrabold text-indigo-900 mb-4 sm:mb-0 flex items-center space-x-3">
              <i class="fas fa-building text-indigo-600"></i>
              <span>Gestion des locataires</span>
            </h1>
            <div v-if="canCreateTenant" class="flex space-x-4">
              <Link :href="route('tenants.create')" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full shadow-lg hover:from-indigo-700 hover:to-indigo-600 transition duration-300 transform hover:scale-105">
                <i class="fas fa-user-plus mr-2"></i>Nouveau locataire
              </Link>
            </div>
          </div>

          <TenantDashboard :activeTenantCount="activeTenantCount" :inactiveTenantCount="inactiveTenantCount" :expiringContracts="expiringContracts" />

          <TenantFilters :filters="filters" :landlords="landlords" @apply-filters="applyFilters" @reset-filters="resetFilters" />

          <TenantTable :paginatedTenants="paginatedTenants" :canUpdateTenant="canUpdateTenant" :canDeleteTenant="canDeleteTenant" @openDeleteModal="openDeleteModal" @editTenant="editTenant" @viewTenant="viewTenant" @createAccount="openCreateAccountModal" />

          <div class="mt-8 flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button @click="previousPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Précédent</span>
                <i class="fas fa-chevron-left"></i>
              </button>
              <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                {{ currentPage }} / {{ totalPages }}
              </span>
              <button @click="nextPage" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Suivant</span>
                <i class="fas fa-chevron-right"></i>
              </button>
            </nav>
          </div>

          <TenantModal :show="showDeleteModal" @close="closeDeleteModal" :tenant="tenantToDelete" @confirm="confirmDelete" />
          <CreateTenantAccountModal :show="showCreateAccountModal" @close="closeCreateAccountModal" :tenant="tenantToCreateAccountFor" />
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, computed, onMounted } from "vue";
  import { usePage, Link, router } from "@inertiajs/vue3";
  import AppLayout from "@/Layouts/AppLayout.vue";
  import TenantFilters from "@/Components/Tenants/TenantFilters.vue";
  import TenantTable from "@/Components/Tenants/TenantTable.vue";
  import TenantModal from "@/Components/Tenants/TenantModal.vue";
  import TenantDashboard from "@/Components/Tenants/TenantDashboard.vue";
  import CreateTenantAccountModal from "@/Components/Tenants/CreateTenantAccountModal.vue";

  const page = usePage();
  const activeTenants = ref(page.props.activeTenants || []);
  const inactiveTenants = ref(page.props.inactiveTenants || []);
  const landlords = ref(page.props.landlords || []);
  const user = page.props.auth.user;
  const userRoles = computed(() => page.props.auth.roles || []);

  const showDeleteModal = ref(false);
  const tenantToDelete = ref(null);
  const showCreateAccountModal = ref(false);
  const tenantToCreateAccountFor = ref(null);
  const currentPage = ref(1);
  const itemsPerPage = 10;

  const filters = ref({
    date: "",
    name: "",
    landlord: "",
    status: "",
  });

  // Dashboard data
  const activeTenantCount = ref(0);
  const inactiveTenantCount = ref(0);
  const expiringContracts = ref(0);

  onMounted(() => {
    // Calculate dashboard data
    activeTenantCount.value = activeTenants.value.length;
    inactiveTenantCount.value = inactiveTenants.value.length;
    expiringContracts.value = [...activeTenants.value, ...inactiveTenants.value].filter((t) => {
      return t.contracts.some((c) => {
        const endDate = new Date(c.end_date);
        const oneMonthFromNow = new Date();
        oneMonthFromNow.setMonth(oneMonthFromNow.getMonth() + 1);
        return endDate <= oneMonthFromNow && c.status === 'active';
      });
    }).length;
  });

  const canCreateTenant = computed(() => {
    return userRoles.value.includes("super_admin") || userRoles.value.includes("admin_entreprise");
  });

  const canUpdateTenant = computed(() => {
    return userRoles.value.includes("super_admin") || userRoles.value.includes("admin_entreprise");
  });

  const canDeleteTenant = computed(() => {
    return userRoles.value.includes("super_admin") || userRoles.value.includes("admin_entreprise");
  });

  const filteredTenants = computed(() => {
    const allTenants = [...activeTenants.value, ...inactiveTenants.value];
    return allTenants.filter((tenant) => {
      const matchesDate = !filters.value.date || (tenant.contracts[0] && new Date(tenant.contracts[0].start_date).toISOString().split("T")[0] === filters.value.date);
      const matchesName = !filters.value.name ||
        (tenant.first_name && tenant.first_name.toLowerCase().includes(filters.value.name.toLowerCase())) ||
        (tenant.last_name && tenant.last_name.toLowerCase().includes(filters.value.name.toLowerCase()));
      const matchesLandlord = !filters.value.landlord || (tenant.contracts[0] && tenant.contracts[0].property && tenant.contracts[0].property.landlord && tenant.contracts[0].property.landlord.id == filters.value.landlord);
      const matchesStatus = !filters.value.status || (tenant.contracts[0] && isActiveTenant(tenant) === (filters.value.status === 'active'));
      return matchesDate && matchesName && matchesLandlord && matchesStatus;
    });
  });

  const totalPages = computed(() => Math.ceil(filteredTenants.value.length / itemsPerPage));

  const paginatedTenants = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredTenants.value.slice(start, end);
  });

  const applyFilters = () => {
    currentPage.value = 1;
  };

  const resetFilters = () => {
    filters.value = {
      date: "",
      name: "",
      landlord: "",
      status: "",
    };
    applyFilters();
  };

  const previousPage = () => {
    if (currentPage.value > 1) {
      currentPage.value--;
    }
  };

  const nextPage = () => {
    if (currentPage.value < totalPages.value) {
      currentPage.value++;
    }
  };

  const openDeleteModal = (tenant) => {
    tenantToDelete.value = tenant;
    showDeleteModal.value = true;
  };

  const closeDeleteModal = () => {
    showDeleteModal.value = false;
    tenantToDelete.value = null;
  };

  const confirmDelete = () => {
    if (tenantToDelete.value) {
      router.delete(route("tenants.destroy", tenantToDelete.value.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          closeDeleteModal();
          if (activeTenants.value.some(t => t.id === tenantToDelete.value.id)) {
            activeTenants.value = activeTenants.value.filter((t) => t.id !== tenantToDelete.value.id);
          } else {
            inactiveTenants.value = inactiveTenants.value.filter((t) => t.id !== tenantToDelete.value.id);
          }
          activeTenantCount.value = activeTenants.value.length;
          inactiveTenantCount.value = inactiveTenants.value.length;
          expiringContracts.value = [...activeTenants.value, ...inactiveTenants.value].filter((t) => {
            return t.contracts.some((c) => {
              const endDate = new Date(c.end_date);
              const oneMonthFromNow = new Date();
              oneMonthFromNow.setMonth(oneMonthFromNow.getMonth() + 1);
              return endDate <= oneMonthFromNow && c.status === 'active';
            });
          }).length;
        },
      });
    }
  };

  const openCreateAccountModal = (tenant) => {
    tenantToCreateAccountFor.value = tenant;
    showCreateAccountModal.value = true;
  };

  const closeCreateAccountModal = () => {
    showCreateAccountModal.value = false;
    tenantToCreateAccountFor.value = null;
  };

  const formatDate = (dateString) => {
    if (!dateString || dateString === "N/A") return "N/A";
    const options = { year: "numeric", month: "long", day: "numeric" };
    const date = new Date(dateString);
    return isNaN(date) ? "Invalid Date" : date.toLocaleDateString("fr-FR", options);
  };

  const getInitials = (firstName, lastName) => {
    return `${firstName.charAt(0)}${lastName.charAt(0)}`.toUpperCase();
  };

  const isActiveTenant = (tenant) => {
    return tenant.contracts.some(c => new Date(c.end_date) >= new Date());
  };

  const editTenant = (tenant) => {
    router.visit(route('tenants.edit', tenant.id));
  };

  const viewTenant = (tenant) => {
    router.visit(route('tenants.show', tenant.id));
  };
  </script>
