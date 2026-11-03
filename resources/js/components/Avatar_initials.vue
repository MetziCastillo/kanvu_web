<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        name: {
            type: String,
            required: true,
        },
        size: {
            type: String,
            default: 'md',
            validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value),
        },
    });

    const initials = computed(() => {
        const parts = props.name.trim().split(/\s+/)

        if (parts.length === 0) return ''
        if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase()

        // primera letra de la primera palabra + primera letra de la última
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
    });

    const sz = computed(() => {
        const map = {
            sm: "w-8 h-8 text-xs",
            md: "w-9 h-9 text-sm",
            lg: "w-12 h-12 text-base",
            xl: "w-16 h-16 text-lg",
        }
        return map[props.size]
    })


</script>
<template>
    <div class="rounded-xl flex items-center justify-center font-semibold flex-shrink-0 bg-olive100 text-olive700"
        :class="sz" :title="name">
        {{initials}}
    </div>
</template>