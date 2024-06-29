<script setup>
import { onMounted } from 'vue';
import Navbar from '@/Components/Navs/Navbar.vue';
import Sidebar from '@/Components/Navs/Sidebar.vue';
import Footer from '@/Components/Ui/Footer.vue';
import { initFlowbite } from 'flowbite'
import Alert from '@/Components/Ui/Alert.vue';

onMounted(() => {
    initFlowbite();
})

</script>

<template>
    <Navbar />

    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        <Sidebar />
        
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <Alert />
            
            <Transition 
                enter-active-class="transition ease-in-out duration-300" 
                enter-from-class="opacity-0" 
                leave-active-class="transition ease-in-out duration-300" 
                leave-to-class="opacity-0"
            >
                <main :key="$page.component">
                    <div class="px-4 pt-6 grid grid-cols-4 gap-4 dark:bg-gray-900">
                        <slot />
                    </div>
                </main>
            </Transition>
    
            <Footer />
        </div>
    </div>
        
</template>
