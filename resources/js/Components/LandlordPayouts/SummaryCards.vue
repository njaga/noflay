<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div
        v-for="(stat, index) in summaryStats"
        :key="index"
        class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 transform"
      >
        <div class="flex items-center justify-between mb-4">
          <div class="p-3 rounded-full bg-indigo-100">
            <component
              :is="getIcon(index)"
              class="w-6 h-6 text-indigo-600"
            />
          </div>
          <span
            class="text-sm font-medium text-indigo-600 bg-indigo-100 py-1 px-2 rounded-full"
          >
            {{ stat.title }}
          </span>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-2">
          {{
            stat.title === "Nombre de versements"
              ? stat.value
              : formatCurrency(stat.value)
          }}
        </p>
        <p class="text-sm text-gray-500">{{ stat.description }}</p>
      </div>
    </div>
  </template>

  <script setup>
  import { TrendingUp, Users, CreditCard } from "lucide-vue-next";

  const props = defineProps({
    summaryStats: {
      type: Array,
      required: true,
    },
  });

  const formatCurrency = (amount) => {
    if (!amount || isNaN(amount)) {
      return "0 F CFA";
    }
    return new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: "XOF",
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(amount);
  };

  const getIcon = (index) => {
    const icons = [TrendingUp, Users, CreditCard];
    return icons[index % icons.length];
  };
  </script>

  <style scoped>
  .grid > div {
    animation: fadeInUp 0.5s ease-out;
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
