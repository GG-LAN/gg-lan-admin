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
        bg-green-700
        rounded-lg
        hover:bg-green-800
        focus:ring-4
        focus:outline-none
        focus:ring-green-300
        dark:focus:ring-green-900
        transition
        ease-in-out
        duration-150"
    >
        <SvgIcon class="w-4 h-4 mr-2" v-if="icon" :icon="icon" :loading="loading" />
        <slot />
    </button>
</template>
