<script setup>
import { computed } from "vue";

const props = defineProps({
    size: {
        type: String,
        default: "4",
    },
    background: {
        type: Boolean,
        default: false,
    },
});

const sizeClass = computed(() => "col-span-" + props.size);

const bgColor = computed(() =>
    props.background
        ? "p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white"
        : ""
);
</script>

<template>
    <div :class="bgColor + ' ' + sizeClass">
        <slot />
    </div>
</template>
