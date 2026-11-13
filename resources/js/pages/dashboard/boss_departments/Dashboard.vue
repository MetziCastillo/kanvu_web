
<script setup>
    import Navbar from '../../../components/navbar.vue';
    import AppIcon from '../../../components/AppIcon.vue';
    import Container1 from '../../../components/dashboard/Container1.vue';
    import TeamCard from '../../../components/dashboard/Teams_card.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main class="flex-1 p-8">
            <Container1 />
                <TeamCard />
        </main>
    </div>

</template>