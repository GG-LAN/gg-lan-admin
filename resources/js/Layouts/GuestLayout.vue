<script setup>
import { onMounted } from 'vue'
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/Ui/ApplicationLogo.vue';
import DarkModeButton from '@/Components/Ui/DarkModeButton.vue'
import { initFlowbite } from 'flowbite'

onMounted(() => {
    initFlowbite();
})
</script>

<template>
    <div class="flex flex-col items-end justify-center dark:bg-gray-900 ">
        <DarkModeButton/>
    </div>
    <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
        <Link href="/">
            <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
        </Link>

        <!-- Card -->
        <div class="w-full max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-lg shadow dark:bg-gray-800">
            <slot />
        </div>
    </div>
</template>
