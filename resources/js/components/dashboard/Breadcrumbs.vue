<script setup>
    import { Link } from '@inertiajs/vue3';
    import AppIcon from '../../components/AppIcon.vue';

    const props = defineProps({
        items: {
            type: Array,
            required: true,
        }
    });
</script>

<template>
    <div class="flex items-center gap-2 px-8 py-3 border-b text-sm bg-white border-stone100">
        <template v-for="(item, index) in items" :key="index">

            <AppIcon v-if="index > 0" name="chevronRight" size="14" color="var(--color-stone300)" />

            <span v-if="index === items.length - 1" class="font-semibold text-olive600">
                {{ item.label }}
            </span>

            <a v-else :href="item.href ?? '#'" class="font-medium hover:underline transition-colors text-stone500">
                {{ item.label }}
            </a>
        </template>
    </div>
</template>