<script setup>
    import Navbar from '../../../components/navbar.vue';
    import AppIcon from '../../../components/AppIcon.vue';
    import Container1 from '../../../components/dashboard/Container1.vue';
    import Container2 from '../../../components/dashboard/Container2.vue';
    import QuickAccess from '../../../components/dashboard/Quick_accesses.vue';
    import CardNote from '../../../components/dashboard/Card_note.vue';
    import CardNextSession from '../../../components/dashboard/Card_next_session.vue';
    import NextAppoiments from '../../../components/dashboard/Next_appoiments.vue';
    import PendingDates from '../../../components/dashboard/Pending_dates.vue';

    defineProps({
        nextAppointment: {
            type: Object,
            default: null
        },
        upcomingAppointments: {
            type: Array,
            default: () => []
        }
    })


</script>

<template>

    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <Container1 />
            <PendingDates />
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 flex flex-col gap-5">
                    <Container2 :next="nextAppointment" />
                    <NextAppoiments />
                </div>
                <div class="flex flex-col gap-5">
                    <QuickAccess />
                    <CardNextSession />
                    <CardNote />
                </div>
            </div>

        </main>
    </div>
</template>