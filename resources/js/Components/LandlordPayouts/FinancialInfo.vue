<template>
    <div
        v-if="financialInfo.length"
        class="mt-8 bg-white p-8 rounded-xl shadow-xl border border-gray-100"
    >
        <h3 class="text-3xl font-bold text-indigo-600 mb-6">Relevé financier</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="bg-gradient-to-r from-indigo-500 to-indigo-700 text-white"
                    >
                        <th class="py-4 px-6 rounded-tl-lg font-semibold">
                            Description
                        </th>
                        <th class="py-4 px-6 font-semibold">Détails</th>
                        <th
                            class="py-4 px-6 rounded-tr-lg font-semibold text-right"
                        >
                            Montant
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(info, index) in financialInfo"
                        :key="index"
                        class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-150 ease-in-out"
                    >
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full mr-3"
                                    :class="getIconBackgroundColor(info)"
                                >
                                    <i
                                        :class="[
                                            'bi',
                                            info.icon,
                                            'text-xl text-white',
                                        ]"
                                    ></i>
                                </div>
                                <span class="font-medium text-gray-700">{{
                                    info.label
                                }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <ul
                                v-if="info.details.length"
                                class="space-y-1 text-sm text-gray-600"
                            >
                                <li
                                    v-for="detail in info.details"
                                    :key="detail.property + '-' + detail.amount"
                                >
                                    {{
                                        detail.tenant
                                            ? `${detail.tenant} - `
                                            : ""
                                    }}{{ detail.property || detail.type }}
                                </li>
                            </ul>
                            <span v-else class="text-sm text-gray-500">-</span>
                        </td>
                        <td
                            class="py-4 px-6 text-right font-semibold"
                            :class="getAmountClass(info)"
                        >
                            {{ formatCurrency(info.amount) }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold text-lg">
                        <td class="py-4 px-6 rounded-bl-lg text-gray-700">
                            Solde Net
                        </td>
                        <td></td>
                        <td
                            class="py-4 px-6 rounded-br-lg text-right"
                            :class="
                                getAmountClass({
                                    amount: calculateNetBalance(),
                                })
                            "
                        >
                            {{ formatCurrency(calculateNetBalance()) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div
        v-else
        class="mt-8 bg-gray-50 p-6 rounded-xl text-center text-gray-500"
    >
        Aucune information financière disponible
    </div>
</template>

<script>
export default {
    props: {
        financialInfo: {
            type: Array,
            required: true,
        },
    },
    methods: {
        formatCurrency(amount) {
            return new Intl.NumberFormat("fr-FR", {
                style: "currency",
                currency: "XOF",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(amount);
        },
        getAmountClass(info) {
            return {
                "text-indigo-600": info.amount > 0,
                "text-red-600": info.amount < 0,
                "text-gray-700": info.amount === 0,
            };
        },
        getIconBackgroundColor(info) {
            const colorMap = {
                "text-green-600": "bg-green-500",
                "text-red-600": "bg-red-500",
                "text-blue-600": "bg-blue-500",
                "text-yellow-600": "bg-yellow-500",
                "text-indigo-600": "bg-indigo-500",
                "text-indigo-600": "bg-indigo-500",
            };
            return colorMap[info.color] || "bg-gray-500";
        },
        calculateNetBalance() {
            return this.financialInfo.reduce((total, info) => {
                if (
                    ["Loyers du mois", "Cautions non reversées"].includes(
                        info.label
                    )
                ) {
                    return total + info.amount;
                } else if (
                    [
                        "Dépenses effectuées",
                        "Commissions retenues",
                        "TVA (18%)",
                    ].includes(info.label)
                ) {
                    return total - info.amount;
                }
                return total;
            }, 0);
        },
    },
};
</script>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");
</style>
