<script setup>
    import Navbar from '../../../components/navbar.vue';
    import AppIcon from '../../../components/AppIcon.vue';
    import Container1 from '../../../components/dashboard/Container1.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <Container1 />
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 flex flex-col gap-5">
                   
                </div>
            </div>
        </main>
    </div>

</template>