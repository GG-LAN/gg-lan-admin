<script setup>
import { computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import { initFlowbite } from 'flowbite'

onMounted(() => {
    initFlowbite();
})

const page = usePage();

const statusClasses = computed(() =>
    page.props.flash.status == "success"
    ? "text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200"
    : "text-red-500   bg-red-100   dark:bg-red-800   dark:text-red-200"
);

</script>

<template>
    <div id="toast" class="fixed top-15 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg" :class="statusClasses">
            <SvgIcon icon="circle-check" v-if="page.props.flash.status =='success'"/>
            <SvgIcon icon="circle-xmark" v-else/>
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ms-3 text-sm font-normal">
            {{ page.props.flash.message }}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</template>