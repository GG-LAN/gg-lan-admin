<script setup>
import SvgIcon from '@/Components/Ui/SvgIcon.vue';

const props = defineProps({
    icon: {
        type: String
    },
    loading: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <button
        type="button"
        class="
        inline-flex
        items-center
        px-3
        py-2
        text-base
        font-medium
        text-center
        text-white
        bg-primary-700
        rounded-lg
        hover:bg-primary-800
        focus:ring-4
        focus:ring-primary-300
        sm:w-auto
        dark:bg-primary-600
        dark:hover:bg-primary-700
        dark:focus:ring-primary-800
        transition
        ease-in-out
        duration-150"
    >
        <SvgIcon class="w-4 h-4 mr-2" v-if="icon" :icon="icon" :loading="loading" />
        <slot />
    </button>
</template>
