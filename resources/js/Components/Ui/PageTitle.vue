<script setup>
import Breadcrumb from '@/Components/Ui/Breadcrumb.vue';

const props = defineProps({
    title: {
        type: String
    },
    breadcrumbs: {
        type: Array,
        required: true
    }
});

</script>

<template>
    <div class="mb-4 col-span-full xl:mb-2">
        <Breadcrumb :breadcrumbs="breadcrumbs"/>

        <!-- Page Title -->
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white" v-if="title">{{ title }}</h1>
        <div class="flex items-center justify-between gap-4" v-else>
            <slot/>
        </div>
    </div>
</template>
