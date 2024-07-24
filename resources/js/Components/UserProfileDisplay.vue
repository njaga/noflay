<template>
    <div class="relative">
      <img
        v-if="$page.props.auth.user.profile_photo_url"
        :src="$page.props.auth.user.profile_photo_url"
        :alt="$page.props.auth.user.name"
        class="h-10 w-10 rounded-full object-cover"
      >
      <div
        v-else
        class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold"
      >
        {{ userInitials }}
      </div>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';
  import { usePage } from '@inertiajs/vue3';

  const page = usePage();

  const userInitials = computed(() => {
    const name = page.props.auth.user.name;
    return name
      .split(' ')
      .map(word => word[0])
      .join('')
      .toUpperCase()
      .slice(0, 2);
  });
  </script>
