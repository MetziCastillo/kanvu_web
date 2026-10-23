<script setup>
    import Navbar from '../../../components/navbar.vue';
    import AppIcon from '../../../components/AppIcon.vue';
    import Container1 from '../../../components/dashboard/Container1.vue';
    import ContainerStatus from '../../../components/dashboard/Container_status.vue';
    import QuickAccess from '../../../components/dashboard/Quick_accesses.vue';
    import CardNote from '../../../components/dashboard/Card_note.vue';

</script>


<template>

    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <Container1 />
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <ContainerStatus />
                <div class="flex flex-col gap-5">
                    <QuickAccess />
                    <CardNote />
                </div>  
            </div>

        </main>
    </div>
</template>