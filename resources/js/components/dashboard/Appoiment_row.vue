<script setup>
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';
    import Statusbadge from './StatusBadge.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);


    const dates = {
        1: [
            {
                area: '7',
                icon: 'leaf',
                subtitle: 'Cuentas Activas',
            },
            {
                area: '5',
                icon: 'graduationCap',
                subtitle: 'Cuentas Activas',
            },
        ],
        5: [
            {
                area: 'Psicología',
                tutor: 'Dra. Ana Reyes',
                icon: 'leaf',
                icon2: 'calendar',
                date: '30 Sept 2026',
                icon3: 'clock',
                time: '10:00 am',
                icon4: 'monitor',
                modality: 'Presencial',
                status: 'coming'
            },
            {
                area: 'Orientación Psicoeducativa',
                tutor: 'Dr. Marco Torrez',
                icon: 'graduationCap',
                icon2: 'calendar',
                date: '08 Oct 2026',
                icon3: 'clock',
                time: '12:30 pm',
                icon4: 'mapPin',
                modality: 'En línea',
                status: 'cancelled'
            }
        ],
    };

    const date = computed(() => dates[rolId.value] ?? []);

</script>
<template>
    <div v-for="card in date" :key="card.area"
        class="flex items-center gap-5 px-6 py-5 border-b last:border-b-0 hover:bg-stone-50 cursor-pointer transition-colors border border-stone100">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-sand">
            <AppIcon :name="card.icon" :size="20" color="var(--color-olive700)" />
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-base truncate text-stone900">{{card.area}}</p>
            <p class="text-sm mt-0.5 truncate text-stone500"> {{card.tutor}}</p>
        </div>
        <div class="hidden md:flex items-center gap-5 text-sm text-stone500">
            <span class="flex items-center gap-1.5">
                <AppIcon :name="card.icon2" :size="15" color="var(--color-stone400)" />
                {{card.date}}
            </span>
            <span class="flex items-center gap-1.5">
                <AppIcon :name="card.icon3" :size="15" color="var(--color-stone400)" />
                {{card.time}}
            </span>
            <span class="flex items-center gap-1.5">
                <AppIcon :name="card.icon4" :size="15" color="var(--color-stone400)" />
                {{card.modality}}
            </span>
        </div>

        <Statusbadge :name="card.status"/>
        <AppIcon name="chevronRight" :size="15" color="var(--color-stone300)" />
    </div>
</template>