<template>
    <div class="space-y-6">
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
          <ul class="space-y-2">
            <li v-for="item in generalInfo" :key="item.label" class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getIconPath(item.icon)" />
              </svg>
              <span class="text-sm text-gray-600">{{ item.label }}:</span>
              <span class="ml-1 text-sm font-medium text-gray-900">{{ item.value }}</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Informations financières</h3>
          <ul class="space-y-2">
            <li v-for="item in financialInfo" :key="item.label" class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getIconPath(item.icon)" />
              </svg>
              <span class="text-sm text-gray-600">{{ item.label }}:</span>
              <span class="ml-1 text-sm font-medium text-gray-900">{{ item.value }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  defineProps({
    generalInfo: {
      type: Array,
      required: true,
    },
    financialInfo: {
      type: Array,
      required: true,
    },
  });

  const getIconPath = (icon) => {
    const iconPaths = {
      calendar: "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z",
      'calendar-check': "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4",
      clock: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
      folder: "M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z",
      cash: "M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z",
      shield: "M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z",
      'graph-up': "M13 7h8m0 0v8m0-8l-8 8-4-4-6 6",
    };
    return iconPaths[icon] || "";
  };
  </script>
