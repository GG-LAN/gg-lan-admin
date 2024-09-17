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
    title: {
        type: String,
    },
});

const sizeClass = computed(() => "col-span-" + props.size);

const bgColor = computed(() =>
    props.background
        ? "p-4 bg-white rounded-lg shadow-md sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white"
        : ""
);
</script>

<template>
    <div :class="bgColor + ' ' + sizeClass" class="space-y-2">
        <h2
            class="text-xl font-semibold text-gray-900 dark:text-gray-400"
            v-if="title"
        >
            {{ __(title) }}
        </h2>
        <slot />
    </div>
</template>
