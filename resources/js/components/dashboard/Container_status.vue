<script setup>
    import { computed } from 'vue';
    import CardUsers from './Card_users.vue';
    import { router } from '@inertiajs/vue3';

    const informUsers = computed(() => page.props.usersInformation);

    const handleUsers = () => {
        router.get('/user_management/Dashboard_um');
    };

</script>

<template>
    <div
        class="bg-white rounded-2xl border lg:col-span-2 p-0 cursor-pointer hover:shadow-sm transition-shadow border border-stone-100">
        <div class="px-6 py-5 border-b flex items-center justify-between border border-stone-100">
            <div>
                <p class="font-semibold text-stone900">Estado de cuentas</p>
                <p class="text-sm mt-1 text-stone500">Resumen operativo, sin información clínica.</p>
            </div>
            <button @click="handleUsers" class="text-sm font-semibold text-olive700">Ver usuarios</button>
        </div>
        <div class="p-6">
            <CardUsers />
        </div>
    </div>
</template>