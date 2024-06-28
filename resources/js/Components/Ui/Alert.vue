<script setup>
import { computed, onMounted, watch, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SvgIcon from "@/Components/Ui/SvgIcon.vue";

onMounted(() => {
})

const page = usePage();
const show = ref(false)

watch(
    () => page.props.flash,
    () => {
        show.value = true;

        setTimeout(() => {
            show.value = false;
        }, 3000);
    }
)

const flash = computed(() =>
    page.props.flash
);

const statusClasses = computed(() => {
    let classes = "";

    switch (flash.value.status) {
        case "success":
            classes = "text-green-800 border-green-300 bg-green-50 dark:text-green-400 dark:bg-gray-800 dark:border-green-800";
        break;

        case "error":
            classes = "text-red-800 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800";
        break;

        case "warning":
            classes = "text-yellow-800 border-yellow-300 bg-yellow-50 dark:text-yellow-400 dark:bg-gray-800 dark:border-yellow-800";
        break;
    
        default: //info
            classes = "text-blue-800 border-blue-300 bg-blue-50 dark:text-blue-400 dark:bg-gray-800 dark:border-blue-800";
        break;
    }
    return classes;
});

const statusIcon = computed(() => {
    let icon = "";

    switch (flash.value.status) {
        case "success":
            icon = "circle-check";
        break;

        case "error":
            icon = "circle-xmark";
        break;

        case "warning":
            icon = "circle-exclamation";
        break;
    
        default: //info
            icon = "circle-info";
        break;

    }

    return icon;
});

</script>

<template>
    <Transition
        enter-active-class="transition ease-in-out duration-300"
        enter-from-class="opacity-0"
        leave-active-class="transition ease-in-out duration-300"
        leave-to-class="opacity-0"
    >
    <div id="flash-alert" v-if="show" class="fixed top-20 right-5 flex items-center p-4 mb-4 border-t-4 rounded-lg" :class="statusClasses" role="alert">
        <SvgIcon :icon="statusIcon" class="flex-shrink-0 w-4 h-4"/>
        <div class="ms-3 text-sm font-medium">
            {{ flash.message }}
        </div>
    </div>
</Transition>

</template>