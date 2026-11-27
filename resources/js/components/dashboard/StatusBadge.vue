<script setup>
  import { computed } from 'vue';

  const props = defineProps({
    name: {
      type: [String, Number],
      required: true,
    },
  });

  const statuses = {
    '1': { label: 'Próxima', classes: 'bg-greenLight text-olive700' },
    'Próxima': { label: 'Próxima', classes: 'bg-greenLight text-olive700' },

    '2': { label: 'Completada', classes: 'bg-stone-100 text-stone-700' },
    'Completada': { label: 'Completada', classes: 'bg-stone-100 text-stone700' },

    '3': { label: 'Cancelada', classes: 'bg-red-100 text-red-800' },
    'Cancelada': { label: 'Cancelada', classes: 'bg-red-100 text-red-800' },
  };

  const status = computed(() => {
    return statuses[props.name] ?? statuses['1'];
  });
</script>

<template>
  <span class="text-xs font-semibold px-2.5 py-1 rounded-full tracking-wide" :class="status.classes">
    {{ status.label }}
  </span>
</template>