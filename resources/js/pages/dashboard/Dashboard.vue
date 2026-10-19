<script setup>
    import Navbar from '../../components/navbar.vue';
    import AppIcon from '../../components/AppIcon.vue';
    import Container1 from '../../components/dashboard/Container1.vue';
    import Container2 from '../../components/dashboard/Container2.vue';

</script>


<template>

    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <Container1 />
            <Container2 />

        </main>
    </div>
</template>