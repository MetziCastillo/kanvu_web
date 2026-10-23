<script setup>
    import AppoimentRow from './Appoiment_row.vue';



</script>
<template>
    <div class="bg-white rounded-2xl hover:shadow-sm transition-shadow border border-stone-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border border-stone-100">
            <p class="font-semibold text-sm text-stone900">Citas próximas</p>
            <button class="text-xs font-semibold text-oliveKanvu">Ver todas</button>

        </div>
            <AppoimentRow />
    </div>
</template>