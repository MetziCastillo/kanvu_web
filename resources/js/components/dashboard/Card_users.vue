<script setup>
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    const cardsUsers = {
        1: [
            {
                title: 'Activas',
                color: 'olive500',
                cantidad: '5'
            },
            {
                title: 'Suspendidas',
                color: 'sandDeep',
                cantidad: '1'
            },
            {
                title: 'Bajas',
                color: 'stone300',
                cantidad: '1'
            }

        ]
    }

    const cards = computed(() => cardsUsers[rolId.value] ?? []);

</script>

<template>
    <div class="h-3 rounded-full overflow-hidden flex flex bg-stone100">
        <div class="bg-olive500" style="width: 10%;"></div>
        <div class="bg-sandDeep" style="width: 50%;"></div>
    </div>
    <div class="grid grid-cols-3 gap-3 mt-5">

        <div class="rounded-xl p-4 bg-stone100" v-for="card in cards" :key="card.title">
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full" :class="`bg-${card.color}`"></span>
                <span class="text-xs font-semibold text-stone500">{{card.title}}</span>
            </div>
            <p class="font-display text-2xl font-semibold text-stone900">{{card.cantidad}}</p>
        </div>

    </div>
</template>