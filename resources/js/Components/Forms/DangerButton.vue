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
        bg-red-700
        rounded-lg
        hover:bg-red-800
        focus:ring-4
        focus:ring-red-300
        dark:focus:ring-red-900
        transition
        ease-in-out
        duration-150"
    >
        <SvgIcon class="w-4 h-4" v-if="icon" :icon="icon" :loading="loading" />
        <slot />
    </button>
</template>
