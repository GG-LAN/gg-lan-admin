<script setup>
import { onMounted } from 'vue'
import axios from 'axios';

const props = defineProps({
});

onMounted(() => {
});


</script>

<template>
    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-100 cursor-pointer grid grid-cols-2">
        <div class="col-span-1">
            <div class="flex flex-col justify-start p-4 leading-normal">
                <h5 class="mb-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">586</h5>
                <p class="mb-3 text-xl font-normal text-gray-700 dark:text-gray-400">Joueurs</p>
            </div>
        </div>
        <div class="flex justify-end col-span-1">
            <SvgIcon icon="user-check" class="w-28 h-28 text-blue-500 dark:text-blue-400"/>
        </div>
    </div>
</template>