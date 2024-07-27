<template>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              {{ column.label }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="(row, rowIndex) in data" :key="rowIndex">
            <td
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
            >
              {{ formatCellValue(row, column) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script setup>


  const props = defineProps({
    data: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      required: true
    }
  });

  const formatCellValue = (row, column) => {
    const value = row[column.key];
    if (column.format) {
      return column.format(value);
    }
    return value;
  };
  </script>
