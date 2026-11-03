<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        status: { type: String, required: true },
    });

    const styleStatus = {
        Activa: 'bg-olive100 text-olive700',
        Suspendida: 'bg-sand text-[#7a6a30]',
        Baja: 'bg-[#fde8e4] text-[#9b3525]'
    };

    const statusStyle = computed(() => styleStatus[props.status] ?? styleStatus.Activa);
</script>

<template>
    <span class="text-xs font-semibold px-2.5 py-1 rounded-full" :class="statusStyle">
        {{ status }}
    </span>
</template>