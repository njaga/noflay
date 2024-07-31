<template>
    <div class="bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-300 hover:shadow-xl">
      <div class="p-6">
        <div class="flex items-start">
          <div :class="`flex-shrink-0 rounded-full p-3 ${bgColorClass} transition-colors duration-300`">
            <component :is="iconComponent" class="h-6 w-6 text-white" aria-hidden="true" />
          </div>
          <div class="ml-5 flex-1 min-w-0">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">
                {{ title }}
              </dt>
              <dd class="mt-1 text-lg font-semibold text-gray-900 break-words">
                {{ formattedValue }}
              </dd>
            </dl>
          </div>
        </div>
      </div>
      <div v-if="link" class="bg-gray-50 px-6 py-4">
        <div class="text-sm">
          <Link :href="link" class="font-medium text-cyan-600 hover:text-cyan-800 transition-colors duration-300">
            Voir les détails
            <span aria-hidden="true" class="ml-1">&rarr;</span>
          </Link>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';
  import { Users, FileText, Home, UserCheck, DollarSign, CreditCard, ShoppingCart, Lock } from 'lucide-vue-next';
  import { Link } from '@inertiajs/vue3';

  const CURRENCY_SYMBOL = "F CFA";

  const props = defineProps({
    title: {
      type: String,
      required: true
    },
    value: {
      type: [Number, String],
      required: true
    },
    icon: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: 'bg-blue-500'
    },
    link: {
      type: String,
      default: null
    },
    valuePrefix: {
      type: String,
      default: ''
    },
    valueSuffix: {
      type: String,
      default: ''
    },
    useKFormat: {
      type: Boolean,
      default: true
    },
    isCurrency: {
      type: Boolean,
      default: false
    }
  });

  const iconComponent = computed(() => {
    const icons = {
      users: Users,
      'file-text': FileText,
      home: Home,
      'user-check': UserCheck,
      'dollar-sign': DollarSign,
      'credit-card': CreditCard,
      'shopping-cart': ShoppingCart,
      'lock': Lock
    };
    return icons[props.icon] || Users;
  });

  const bgColorClass = computed(() => {
    return `${props.color} hover:${props.color.replace('bg-', 'bg-')}`;
  });

  const formatNumberWithK = (value) => {
    if (value >= 1000000) {
      return (value / 1000000).toFixed(1) + 'M';
    } else if (value >= 1000) {
      return (value / 1000).toFixed(1) + 'K';
    }
    return value.toString();
  };

  const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'XOF',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(value);
  };

  const formattedValue = computed(() => {
    let value = props.value;
    if (typeof value === 'number') {
      if (props.isCurrency) {
        value = formatCurrency(value);
      } else {
        value = props.useKFormat ? formatNumberWithK(value) : value.toLocaleString();
      }
    }
    return `${props.valuePrefix}${value}${props.valueSuffix}`;
  });
  </script>
