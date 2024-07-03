<template>
    <div
      :class="[
        'bg-white rounded-lg overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl',
        viewMode === 'grid' ? 'transform hover:-translate-y-2' : 'flex'
      ]"
    >
      <div :class="[viewMode === 'grid' ? 'w-full' : 'w-1/3']">
        <img
          :src="getPropertyImage(property)"
          :alt="property.name"
          class="w-full h-48 object-cover"
        />
      </div>
      <div :class="[viewMode === 'grid' ? 'p-5' : 'w-2/3 p-5']">
        <div class="flex justify-between items-start mb-2">
          <h3 class="text-xl font-semibold text-gray-900 truncate">{{ property.name }}</h3>
          <span
            :class="[
              'px-2 py-1 text-xs font-semibold rounded-full',
              property.available_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
            ]"
          >
            {{ property.available_count > 0 ? 'Disponible' : 'Indisponible' }}
          </span>
        </div>
        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ property.description }}</p>
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center space-x-2 text-gray-500 text-sm">
            <i class="fas fa-bed"></i>
            <span>{{ property.bedrooms }} chambres</span>
          </div>
          <div class="flex items-center space-x-2 text-gray-500 text-sm">
            <i class="fas fa-bath"></i>
            <span>{{ property.bathrooms }} SDB</span>
          </div>
          <div class="flex items-center space-x-2 text-gray-500 text-sm">
            <i class="fas fa-home"></i>
            <span>{{ property.surface }} m²</span>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-2xl font-bold text-indigo-600">{{ formatPrice(property.price) }}</span>
          <button
            @click="showDetails"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-300"
          >
            Voir détails
          </button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';

  const props = defineProps({
    property: {
      type: Object,
      required: true
    },
    viewMode: {
      type: String,
      default: 'grid'
    }
  });

  const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(price);
  };

  const getPropertyImage = (property) => {
    if (property.photos && property.photos.length) {
      // Assuming the photos are stored as JSON strings in the database
      const parsedPhotos = JSON.parse(property.photos);
      if (parsedPhotos.length > 0) {
        // Remove the leading "public/" from the path if it exists
        const photoPath = parsedPhotos[0].replace(/^public\//, '');
        return `/storage/${photoPath}`;
      }
    }
    return 'https://via.placeholder.com/300x200?text=Pas+d%27image'; // Fallback image
  };

  const showDetails = () => {
    // Implement the logic to show property details
    // This could be navigating to a new page or opening a modal
    console.log('Show details for property:', props.property.id);
  };
  </script>

  <style scoped>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  </style>
