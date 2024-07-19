<template>
    <div class="card h-100 border-0 shadow-sm feature-card">
      <div class="card-body d-flex flex-column align-items-center text-center">
        <div class="feature-icon mb-3">
          <i :class="['bi', icon, 'fs-1 text-indigo-600']"></i>
        </div>
        <h3 class="card-title fs-4 fw-semibold text-indigo-800 mb-3">{{ title }}</h3>
        <p class="card-text text-indigo-600">{{ description }}</p>
      </div>
    </div>
  </template>

  <script>
  import { defineComponent } from 'vue'

  export default defineComponent({
    name: 'FeatureCard',
    props: {
      icon: {
        type: String,
        required: true
      },
      title: {
        type: String,
        required: true
      },
      description: {
        type: String,
        required: true
      }
    }
  })
  </script>

  <style scoped>
  .feature-card {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  }

  .feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
  }

  .feature-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e0e7ff; /* light indigo background */
    border-radius: 50%;
  }

  .text-indigo-600 {
    color: #4f46e5;
  }

  .text-indigo-800 {
    color: #3730a3;
  }
  </style>
