<template>
    <div>
      <div class="border-b border-gray-200 mb-4">
        <nav class="-mb-px flex space-x-8">
          <a
            v-for="tab in tabs"
            :key="tab.id"
            href="#"
            @click.prevent="selectTab(tab.id)"
            :class="{
              'border-indigo-500 text-indigo-600': activeTab === tab.id,
              'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== tab.id
            }"
            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
          >
            {{ tab.name }}
          </a>
        </nav>
      </div>
      <div>
        <slot :name="activeTab"></slot>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      tabs: {
        type: Array,
        required: true
      },
      modelValue: {
        type: String,
        required: true
      }
    },
    computed: {
      activeTab: {
        get() {
          return this.modelValue;
        },
        set(value) {
          this.$emit('update:modelValue', value);
        }
      }
    },
    methods: {
      selectTab(tabId) {
        this.activeTab = tabId;
      }
    }
  }
  </script>
