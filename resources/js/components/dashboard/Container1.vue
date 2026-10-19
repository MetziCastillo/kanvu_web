<script setup>
    import AppIcon from '../../components/AppIcon.vue';
    import Card from './Card.vue';
</script>

<template>
    <div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <p class="text-base font-medium mb-1 text-stone-500">Hola</p>
                <h1 class="font-display text-2xl sm:text-3xl font-semibold text-stone-900">Sofía Ramírez</h1>
            </div>
            <button class="bg-oliveKanvu text-white flex justify-center items-center gap-2 px-6 sm:px-8 py-3 rounded-xl sm:rounded-xl shadow-sm hover:opacity-95 transition-opacity w-full md:w-auto">
                <AppIcon name="plus" :size="18" color="white" />
                Nueva cita
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
            <Card />
        </div>
    </div>
</template>