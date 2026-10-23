<script setup>
    import AppIcon from '../../components/AppIcon.vue';
    import Card from './Card.vue';
    import Statusbadge from './StatusBadge.vue';

</script>

<template>
    <div class="rounded-2xl p-6 flex flex-col gap-4 bg-oliveKanvu">
        <div class="flex items-center justify-between">
            <p class="text-xs font-semibold uppercase tracking-wider text-olive200">Próxima Cita</p>
            <Statusbadge name="coming" />
        </div>
        <div>
            <p class="font-display text-xl font-semibold text-white mb-1">Psicología</p>
            <p class="text-sm text-white">Dra. Ana Reyes</p>
        </div>
        <div class="flex flex-wrap gap-5 text-sm text-olive200">
            <span class="flex items-center gap-2">
                <AppIcon name="calendar" :size="14" color="var(--color-olive200)" />
                30 sep 2026
            </span>
            <span class="flex items-center gap-2">
                <AppIcon name="clock" :size="14" color="var(--color-olive200)" />
                10:00 am
            </span>
            <span class="flex items-center gap-2">
                <AppIcon name="mapPin" :size="14" color="var(--color-olive200)" />
                Edificio C, Of. 204
            </span>
        </div>
        <div class="flex gap-3 pt-1">
            <button class="text-sm font-semibold px-5 py-2 rounded-xl bg-white transition-colors bg-olive700">
                Ver detalle
            </button>
            <button class="text-sm font-medium px-5 py-2 rounded-xl transition-colors text-white"
                style="background-color: rgba(255, 255, 255, 0.15);">
                Reagendar
            </button>
        </div>
    </div>
</template>