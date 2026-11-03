<script setup>
    import AppIcon from './AppIcon.vue';

    defineProps({
        title: {
            type: String,
        },
        label: {
            type: String,
            required: true,
        },
        placeholder: {
            type: String,
            required: true
        },
        iconComponent: {
            type: String
        },
        type: {
            type: String,
            required: true
        }
    })

    const model = defineModel({ type: String, default: '' })

    function onFocus(e) {
        e.target.style.borderColor = 'var(--color-olive500)'
    }
    function onBlur(e) {
        e.target.style.borderColor = 'var(--color-stone300)'
    }

</script>

<template>
    <div class="flex flex-col gap-1.5">
        <label :for="label" class="text-xs font-semibold uppercase tracking-wider text-stone500">{{title}} </label>
        <div class="relative">
            <span v-if="iconComponent"
                class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-stone300">
                <AppIcon :name="iconComponent" size="16" color="var(--color-stone500)" />
            </span>
            <input v-model="model" :type="type" :id="label" :placeholder="placeholder" :name="label"
                class="w-full rounded-xl bg-white text-sm outline-none transition-all py-3 px-4 text-stone900 border border-stone300"
                :style="{ paddingLeft: iconComponent ? '2.75rem' : '1rem' }" @focus="onFocus" @blur="onBlur" />
        </div>
    </div>
</template>