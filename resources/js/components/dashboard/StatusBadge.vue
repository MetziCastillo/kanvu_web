<script setup>
  import { computed } from 'vue';

  const props = defineProps({
    name: {
      type: String,
      required: true,
    },
  });

  const statuses = {
    coming: {
      label: 'Próxima',
      classes: 'bg-greenLight text-olive700',
    },
    completed: {
      label: 'Completada',
      classes: 'bg-stone-100 text-stone-700',
    },
    cancelled: {
      label: 'Cancelada',
      classes: 'bg-red-100 text-red-800',
    },
  };

  const status = computed(() => {
    return statuses[props.name] ?? statuses.coming;
  });
</script>

<template>
  <span class="text-xs font-semibold px-2.5 py-1 rounded-full tracking-wide" :class="status.classes">
    {{ status.label }}
  </span>
</template>