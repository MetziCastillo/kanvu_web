<script setup>
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    const textCards = {
        1: {
            title: 'Administración segura',
            subtitle: 'Este panel administra cuentas y catálogos. No muestra motivos de consulta ni expedientes clínicos.',
            icon: 'shield',
        },
        5: {
            title: 'Tip de bienestar',
            subtitle: 'Dedica 10 minutos a respirar: inhala 4 segundos, sostén 4 y exhala 6. Reduce el estrés y mejora tu concentración.',
            icon: 'lightbulb'
        }
    }

    const cards = computed(() => textCards[rolId.value] ?? {});

</script>

<template>
    <div class="rounded-2xl p-5 flex gap-3 bg-olive50">
        <AppIcon :name="cards.icon" :size="19" color="var(--color-olive700)"/>
        <div>
            <p class="text-sm font-semibold text-olive700">{{cards.title}}</p>
            <p class="text-xs leading-relaxed mt-1 text-olive700">{{cards.subtitle}}</p>
        </div>
    </div>
</template>