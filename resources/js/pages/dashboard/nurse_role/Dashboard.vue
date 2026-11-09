<script setup>
    import { ref, computed } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';

    import Navbar from '../../../components/navbar.vue';
    import AppIcon from '../../../components/AppIcon.vue';
    import Container1 from '../../../components/dashboard/Container1.vue';
    import DateSeccion from '../../../components/appointments/Dates_seccion.vue';

    const page = usePage();
    const mAppointments = computed(() => page.props.medicalAppointments ?? []);


</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <Container1 />
            <DateSeccion :appointments="mAppointments" />
        </main>
    </div>
</template>