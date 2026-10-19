<script setup>
    import AppIcon from '../../components/AppIcon.vue';

    const cards = [
        {
            number: '6',
            icon: 'calendar',
            subtitle: 'Citas totales',
        },
        {
            number: '5',
            icon: 'check',
            subtitle: 'Asistencias',
        },
        {
            number: '10',
            icon: 'pending',
            subtitle: 'Pendientes de agendar',
        },
        {
            number: '2',
            icon: 'clock',
            subtitle: 'Próximas',
        },
    ];
</script>

<template>
    <div v-for="card in cards" :key="card.subtitle"
        class="p-5 bg-white rounded-2xl hover:shadow-sm transition-shadow border border-stone-100">
        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 bg-[#F5F8EE]">
                <AppIcon :name="card.icon" :size="18" color="var(--color-oliveKanvu)" />
            </div>

            <div>
                <p class="font-display text-3xl font-semibold text-stone900">
                    {{ card.number }}
                </p>

                <p class="text-sm font-medium mt-0.5 text-stone500">
                    {{ card.subtitle }}
                </p>
            </div>

        </div>

    </div>

</template>