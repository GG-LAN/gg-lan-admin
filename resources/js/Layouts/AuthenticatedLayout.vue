<script setup>
import { onMounted } from 'vue';
import Navbar from '@/Components/Navs/Navbar.vue';
import Sidebar from '@/Components/Navs/Sidebar.vue';
import Footer from '@/Components/Ui/Footer.vue';
import { initFlowbite } from 'flowbite'
import Toast from '@/Components/Ui/Toast.vue';

onMounted(() => {
    initFlowbite();
})

</script>

<template>
    <Navbar />

    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        <Sidebar />
        
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <main>
                <div class="px-4 pt-6 grid grid-cols-4 gap-4 dark:bg-gray-900">
                    <!-- <Toast v-if="$page.props.flash.message"/> -->
                    <slot />
                </div>
            </main>
    
            <Footer />
        </div>
    </div>
        
</template>
