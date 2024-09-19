<script setup>
defineProps({
    value: {
        type: String,
    },
    required: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <label
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400"
    >
        <span v-if="value">{{ __(value) }}</span>
        <span v-else><slot /></span>
        <span
            class="text-red-500 dark:text-red-400 font-semibold"
            v-if="required"
        >
            *
        </span>
    </label>
</template>
