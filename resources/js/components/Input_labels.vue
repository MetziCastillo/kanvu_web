<script setup>
    import { useAttrs } from 'vue';
    import AppIcon from './AppIcon.vue';

    defineOptions({
        // Propaga class, @keydown, @focus, etc. al <input> en vez del <div> raíz
        inheritAttrs: false,
    });

    const props = defineProps({
        title: { type: String, default: '' },
        label: { type: String, required: true },
        placeholder: { type: String, default: '' },
        iconComponent: { type: String, default: '' },
        type: { type: String, default: 'text' },
        readonly: { type: Boolean, default: false },
        disabled: { type: Boolean, default: false },
    });

    const model = defineModel({ type: [String, Number], default: '' });

    const attrs = useAttrs();

    function onFocus(e) {
        e.target.style.borderColor = 'var(--color-olive500)';
    }
    function onBlur(e) {
        e.target.style.borderColor = 'var(--color-stone300)';
    }
</script>

<template>
    <div class="flex flex-col gap-1.5">
        <label v-if="title" :for="label" class="text-xs font-semibold uppercase tracking-wider text-stone500">
            {{ title }}
        </label>

        <div class="relative">
            <span v-if="iconComponent"
                class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-stone300">
                <AppIcon :name="iconComponent" size="16" color="var(--color-stone500)" />
            </span>

            <input v-model="model" v-bind="attrs" :type="type" :id="label" :name="label" :placeholder="placeholder"
                :readonly="readonly" :disabled="disabled"
                class="w-full rounded-xl bg-white text-sm outline-none transition-all py-3 px-4 text-stone900 border border-stone300 disabled:opacity-60 disabled:cursor-not-allowed read-only:bg-stone-50 read-only:cursor-not-allowed"
                :style="{ paddingLeft: iconComponent ? '2.75rem' : '1rem' }" @focus="onFocus" @blur="onBlur" />
        </div>
    </div>
</template>