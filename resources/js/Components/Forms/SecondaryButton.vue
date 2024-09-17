<script setup>
import SvgIcon from "@/Components/Ui/SvgIcon.vue";

const props = defineProps({
    icon: {
        type: String,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        type="button"
        class="inline-flex items-center px-3 py-2 space-x-2 text-base font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 transition ease-in-out duration-150"
        :disabled="loading"
        :class="{ 'opacity-25': loading }"
    >
        <SvgIcon class="w-4 h-4" v-if="icon" :icon="icon" :loading="loading" />
        <slot />
    </button>
</template>
