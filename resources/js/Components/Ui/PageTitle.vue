<script setup>
import Breadcrumb from "@/Components/Ui/Breadcrumb.vue";

const props = defineProps({
    title: {
        type: String,
    },
    breadcrumbs: {
        type: Array,
    },
});
</script>

<template>
    <div class="col-span-full space-y-4">
        <Breadcrumb :breadcrumbs="breadcrumbs" />

        <!-- Page Title -->
        <h1 class="text-xl font-bold sm:text-2xl" v-if="title">
            {{ title }}
        </h1>
        <div class="flex items-center justify-between gap-4" v-else>
            <slot />
        </div>
    </div>
</template>
