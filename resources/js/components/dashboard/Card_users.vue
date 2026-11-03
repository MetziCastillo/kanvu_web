<script setup>
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';

    const page = usePage();
    const usuario = computed(() => page.props.auth.user);
    const rolId = computed(() => usuario.value?.role?.id);

    const informUsers = computed(() => page.props.usersInformation ?? []);

    const activeUsers = computed(() => informUsers.value.filter(u => Number(u.status_id) === 1).length);
    const suspendUsers = computed(() => informUsers.value.filter(u => Number(u.status_id) === 2).length);
    const deactivatedUsers = computed(() => informUsers.value.filter(u => Number(u.status_id) === 3).length);

    const totalUsers = computed(() => activeUsers.value + suspendUsers.value + deactivatedUsers.value);

    const activePercent = computed(() => totalUsers.value ? (activeUsers.value / totalUsers.value) * 100 : 0);
    const suspendPercent = computed(() => totalUsers.value ? (suspendUsers.value / totalUsers.value) * 100 : 0);
    const deactivatedPercent = computed(() => totalUsers.value ? (deactivatedUsers.value / totalUsers.value) * 100 : 0);

    const cards = computed(() => {
        if (Number(rolId.value) !== 1) return [];

        return [
            {
                title: 'Activas',
                bgCircle: 'bg-olive500',
                cantidad: activeUsers.value
            },
            {
                title: 'Suspendidas',
                bgCircle: 'bg-sandDeep',
                cantidad: suspendUsers.value
            },
            {
                title: 'Bajas',
                bgCircle: 'bg-stone300',
                cantidad: deactivatedUsers.value
            }
        ];
    });
</script>

<template>
    <div class="h-3 rounded-full overflow-hidden flex bg-stone100">
        <div class="bg-olive500 transition-all duration-500" :style="{ width: activePercent + '%' }"></div>
        <div class="bg-sandDeep transition-all duration-500" :style="{ width: suspendPercent + '%' }"></div>
        <div class="bg-stone300 transition-all duration-500" :style="{ width: deactivatedPercent + '%' }"></div>
    </div>

    <div class="grid grid-cols-3 gap-3 mt-5">
        <div class="rounded-xl p-4 bg-stone100" v-for="card in cards" :key="card.title">
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full" :class="card.bgCircle"></span>
                <span class="text-xs font-semibold text-stone500">{{ card.title }}</span>
            </div>
            <p class="font-display text-2xl font-semibold text-stone900">{{ card.cantidad }}</p>
        </div>
    </div>
</template>