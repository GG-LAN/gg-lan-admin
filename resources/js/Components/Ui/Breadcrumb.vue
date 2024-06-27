<script setup>
import { onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';

const props = defineProps({
    breadcrumbs: {
        type: Array
    }
});

onMounted(() => {
})
</script>

<template>
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
            <li class="inline-flex items-center py-2">
                <Link :href="route('dashboard')" class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500">
                    <SvgIcon icon="house" class="w-5 h-5 mr-2 text-gray-400"/>
                    Dashboard
                </Link>
            </li>
            
            <li v-for="item in breadcrumbs">
                <!-- Normal Crumb -->
                <div class="flex items-center" v-if="!item.choices">
                    <SvgIcon icon="angle-right" class="w-4 h-4 text-gray-400"/>

                    <Link :href="item.route" class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-primary-500" v-if="!item.active">
                        {{ item.label }}
                    </Link>

                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400" v-else>
                        {{ item.label }}
                    </span>
                </div>

                <!-- Dropdown Crumb -->
                <div class="flex items-center" v-else>
                    <SvgIcon icon="angle-right" class="w-4 h-4 text-gray-400"/>
    
                    <button 
                        id="breadcrumbDropdownButton"
                        data-dropdown-toggle="breadcrumb-dropdown"
                        class="inline-flex items-center px-3 py-2 text-center text-gray-700 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-900 dark:hover:bg-gray-800 dark:text-gray-300 dark:focus:ring-gray-700"
                    >
                        <div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2" v-if="'connected' in item && item.connected"></div>
                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2" v-if="'connected' in item && !item.connected"></div>
                        {{ item.label }}

                        <SvgIcon icon="angle-down" class="w-3.5 h-3.5 ms-2.5 text-gray-400"/>
                    </button>
                    <div id="breadcrumb-dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
                            <li v-for="choice in item.choices">
                                <Link :href="choice.route" v-if="choice" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex items-center">
                                    <div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2" v-if="'connected' in choice && choice.connected"></div>
                                    <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2" v-if="'connected' in choice && !choice.connected"></div>
                                    
                                    {{ choice.label }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ol>
    </nav>
</template>
