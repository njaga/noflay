<template>
    <AppLayout title="Créer un versement">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-xl">
                <h2 class="text-3xl font-extrabold text-indigo-800 mb-6">
                    Nouveau versement
                </h2>
                <form @submit.prevent="showConfirmModal = true" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sélection du bailleur -->
                        <div class="relative col-span-2">
                            <label for="landlord_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Bailleur</label>
                            <select id="landlord_id" v-model="form.landlord_id" @change="fetchLandlordDetails"
                                class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                required>
                                <option value="" disabled>Sélectionnez un bailleur</option>
                                <option v-for="landlord in landlords" :key="landlord.id" :value="landlord.id">
                                    {{ landlord.first_name }} {{ landlord.last_name }}
                                </option>
                            </select>
                        </div>

                        <!-- Sélection du mois -->
                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Mois du
                                versement</label>
                            <select id="month" v-model="form.month" @change="updateFinancialInfo"
                                class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                required>
                                <option value="" disabled>Sélectionnez un mois</option>
                                <option v-for="month in availableMonths" :key="month.value" :value="month.value">{{
                                    month.label }}</option>
                            </select>
                        </div>

                        <!-- Type de paiement -->
                        <div>
                            <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">Type de
                                paiement</label>
                            <select id="payment_type" v-model="form.payment_type" @change="updateFinancialInfo"
                                class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                required>
                                <option value="selected_month">Payer le mois sélectionné uniquement</option>
                                <option value="full_balance">Payer tout le solde disponible</option>
                            </select>
                        </div>

                        <!-- Date de versement -->
                        <div>
                            <label for="payout_date" class="block text-sm font-medium text-gray-700 mb-1">Date de
                                versement</label>
                            <input type="date" id="payout_date" v-model="form.payout_date"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                required />
                        </div>

                        <!-- Moyen de paiement -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Moyen de
                                paiement</label>
                            <select id="payment_method" v-model="form.payment_method" @change="updatePaymentDetails"
                                class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                required>
                                <option value="" disabled>Sélectionnez un moyen de paiement</option>
                                <option value="bank">Banque</option>
                                <option value="cash">Caisse</option>
                                <option value="cheque_cash">Chèque & Caisse</option>
                            </select>
                        </div>

                        <!-- Champs conditionnels basés sur le moyen de paiement -->
                        <div v-if="form.payment_method === 'bank' || form.payment_method === 'cheque_cash'">
                            <label for="cheque_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro du
                                chèque</label>
                            <input type="text" id="cheque_number" v-model="form.cheque_number"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out" />
                        </div>
                        <div v-if="form.payment_method === 'cheque_cash'">
                            <label for="cheque_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant du
                                chèque</label>
                            <input type="number" id="cheque_amount" v-model="form.cheque_amount"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out" />
                        </div>
                        <div v-if="form.payment_method === 'cheque_cash'">
                            <label for="cash_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant en
                                caisse</label>
                            <input type="number" id="cash_amount" v-model="form.cash_amount"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out" />
                        </div>
                    </div>

                    <!-- Informations financières du bailleur -->
                    <FinancialInfo :financialInfo="financialInfo" />

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                            :disabled="loading">
                            <span v-if="loading" class="mr-2">
                                <i class="bi bi-arrow-repeat animate-spin"></i>
                            </span>
                            {{ loading ? "Chargement..." : "Créer le versement" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div v-if="showConfirmModal"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer le versement</h3>
                <p class="text-sm text-gray-500 mb-4">Êtes-vous sûr de vouloir effectuer ce versement de {{
                    formatCurrency(totalAmount.value) }} ?</p>
                <div class="mt-5 sm:mt-6 flex justify-end space-x-3">
                    <button type="button"
                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                        @click="showConfirmModal = false">Annuler</button>
                    <button type="button"
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                        @click="submit">Confirmer</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { ref, computed, onMounted, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import FinancialInfo from "@/Components/LandlordPayouts/FinancialInfo.vue";

export default {
    components: {
        AppLayout,
        FinancialInfo,
    },
    setup() {
        const { props } = usePage();
        const landlords = ref(props.landlords || []);
        const form = ref({
            landlord_id: "",
            payout_date: new Date().toISOString().split("T")[0],
            month: "",
            payment_type: "selected_month",
            payment_method: "",
            cheque_number: "",
            cheque_amount: 0,
            cash_amount: 0,
        });

        const landlordDetails = ref(null);
        const financialInfo = ref([]);
        const loading = ref(false);
        const showConfirmModal = ref(false);
        const totalAmount = ref(0);

        const availableMonths = computed(() => {
            const currentDate = new Date();
            return Array.from({ length: 12 }, (_, i) => {
                const date = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth() - i,
                    1
                );
                return {
                    value: `${date.getFullYear()}-${String(
                        date.getMonth() + 1
                    ).padStart(2, "0")}`,
                    label: date.toLocaleString("fr-FR", {
                        month: "long",
                        year: "numeric",
                    }),
                };
            });
        });

        const fetchLandlordDetails = async () => {
            if (form.value.landlord_id) {
                loading.value = true;
                try {
                    const response = await fetch(
                        route(
                            "landlord-payouts.details",
                            form.value.landlord_id
                        )
                    );
                    landlordDetails.value = await response.json();
                    console.log("Raw Landlord Details:", landlordDetails.value);
                    updateFinancialInfo();
                } catch (error) {
                    console.error("Error fetching landlord details:", error);
                } finally {
                    loading.value = false;
                }
            }
        };

        const updateFinancialInfo = () => {
            if (landlordDetails.value) {
                const agencyPercentage =
                    parseFloat(landlordDetails.value.agency_percentage) || 12;
                const monthlyRent =
                    parseFloat(landlordDetails.value.monthlyRent) || 0;
                const totalCautionAmount =
                    parseFloat(landlordDetails.value.totalCautionAmount) || 0;
                const monthlyExpenses =
                    parseFloat(landlordDetails.value.monthlyExpenses) || 0;

                const rentCommission = monthlyRent * (agencyPercentage / 100);
                const cautionCommission =
                    totalCautionAmount * (agencyPercentage / 100);

                let totalCommissions, tva, netBalance, cautionAmount;

                if (form.value.payment_type === "full_balance") {
                    totalCommissions = rentCommission + cautionCommission;
                    cautionAmount = totalCautionAmount;
                    tva = (monthlyRent + totalCommissions) * 0.18;
                } else {
                    totalCommissions = rentCommission;
                    cautionAmount = 0;
                    tva = monthlyRent * 0.18;
                }

                netBalance =
                    monthlyRent +
                    cautionAmount -
                    monthlyExpenses -
                    totalCommissions -
                    tva;

                if (isNaN(netBalance)) {
                    console.error("Erreur dans le calcul du solde disponible", {
                        monthlyRent,
                        cautionAmount,
                        monthlyExpenses,
                        totalCommissions,
                        tva,
                    });
                    netBalance = 0;
                }

                console.log("Valeurs utilisées dans le calcul:");
                console.log("monthlyRent:", monthlyRent);
                console.log("cautionAmount:", cautionAmount);
                console.log("monthlyExpenses:", monthlyExpenses);
                console.log("totalCommissions:", totalCommissions);
                console.log("tva:", tva);
                console.log("netBalance:", netBalance);

                financialInfo.value = [
                    {
                        label: "Loyers du mois",
                        amount: monthlyRent,
                        details:
                            landlordDetails.value.monthlyRentDetails?.map(
                                (detail) => ({
                                    tenant: detail.tenant,
                                    property: detail.property,
                                    amount: parseFloat(detail.amount) || 0,
                                })
                            ) || [],
                        icon: "bi-graph-up",
                        color: "text-green-600",
                        month: form.value.month,
                    },
                    {
                        label: "Cautions non reversées",
                        amount: cautionAmount,
                        details:
                            form.value.payment_type === "full_balance"
                                ? landlordDetails.value.cautionDetails?.map(
                                    (detail) => ({
                                        tenant: detail.tenant,
                                        property: detail.property,
                                        amount:
                                            parseFloat(detail.amount) || 0,
                                    })
                                ) || []
                                : [],
                        icon: "bi-cash-stack",
                        color: "text-yellow-600",
                        month: form.value.month,
                    },
                    {
                        label: "Dépenses effectuées",
                        amount: monthlyExpenses,
                        details:
                            landlordDetails.value.expenseDetails?.map(
                                (detail) => ({
                                    tenant: "",
                                    property: detail.description,
                                    amount: parseFloat(detail.amount) || 0,
                                })
                            ) || [],
                        icon: "bi-graph-down",
                        color: "text-red-600",
                        month: form.value.month,
                    },
                    {
                        label: "Commissions retenues",
                        amount: totalCommissions,
                        details: [
                            {
                                type: "Commission sur la caution",
                                amount:
                                    form.value.payment_type === "full_balance"
                                        ? cautionCommission
                                        : 0,
                            },
                            {
                                type: "Commission sur le loyer encaissé",
                                amount: rentCommission,
                            },
                        ],
                        icon: "bi-credit-card",
                        color: "text-blue-600",
                        month: form.value.month,
                    },
                    {
                        label: "TVA (18%)",
                        amount: tva,
                        details: [],
                        icon: "bi-percent",
                        color: "text-purple-600",
                        month: form.value.month,
                    },
                    {
                        label: "Solde disponible",
                        amount: netBalance,
                        details: [],
                        icon: "bi-wallet2",
                        color: "text-indigo-600",
                        month: form.value.month,
                    },
                ];

                updateAmount();
            }
        };

        const updateAmount = () => {
            totalAmount.value =
                form.value.payment_type === "full_balance"
                    ? financialInfo.value.find(info => info.label === "Solde disponible").amount
                    : financialInfo.value.find(info => info.label === "Loyers du mois").amount;
        };

        const updatePaymentDetails = () => {
            if (form.value.payment_method !== "cheque_cash") {
                form.value.cheque_number = "";
                form.value.cheque_amount = 0;
                form.value.cash_amount = 0;
            }
        };

        const formatCurrency = (amount) => {
            return new Intl.NumberFormat("fr-FR", {
                style: "currency",
                currency: "XOF",
            }).format(amount);
        };

        const submit = () => {
            if (
                form.value.payment_method === "cheque_cash" &&
                parseFloat(form.value.cheque_amount) +
                parseFloat(form.value.cash_amount) !==
                parseFloat(totalAmount.value)
            ) {
                alert(
                    "Le montant du chèque et le montant en caisse doivent être égaux au montant total."
                );
                return;
            }

            loading.value = true;
            router.post(route("landlord-payouts.store"), form.value, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    showConfirmModal.value = false;
                    loading.value = false;
                    // Réinitialiser le formulaire ou rediriger l'utilisateur
                },
                onError: () => {
                    loading.value = false;
                    // Gérer les erreurs ici
                },
            });
        };

        onMounted(() => {
            if (form.value.landlord_id) {
                fetchLandlordDetails();
            }
        });

        watch(() => form.value.month, updateFinancialInfo);
        watch(() => form.value.payment_type, updateFinancialInfo);

        return {
            landlords,
            form,
            landlordDetails,
            financialInfo,
            loading,
            showConfirmModal,
            availableMonths,
            fetchLandlordDetails,
            updateFinancialInfo,
            updateAmount,
            updatePaymentDetails,
            formatCurrency,
            submit,
            totalAmount,
        };
    },
};
</script>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");

.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right,
            var(--tw-gradient-stops));
}
</style>
