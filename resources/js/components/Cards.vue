<script setup>
    import { computed, getCurrentInstance } from "vue";

    const props = defineProps({
        className: { type: String, default: "" },
        padding: { type: String, default: "p-5" },
    })

    const emit = defineEmits(["click"])

    const isClickable = computed(
        () => !!getCurrentInstance()?.vnode.props?.onClick
    )

    const cardClasses = computed(() => [
        "bg-white rounded-2xl border",
        props.padding,
        isClickable.value
            ? "cursor-pointer hover:shadow-sm transition-shadow"
            : "",
        props.className,
    ])

    const cardStyle = { borderColor: "#f0f0eb" }
</script>

<template>
    <div :class="cardClasses" :style="cardStyle" @click="emit('click')">
        <slot />
    </div>
</template>