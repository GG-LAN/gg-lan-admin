<script setup>
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import { computed, onMounted } from 'vue';

const props = defineProps({
    table: {
        type: Object
    }
})

onMounted(() => {
});
</script>

<template>
    <div>
        <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
            <SvgIcon icon="filter" class="w-4 h-4 mr-2"/>
            Filtrer
            <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
            </svg>
        </button>
        
        <!-- Dropdown menu -->
        <div id="filterDropdown" class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
                Ajouter un filtre
            </h6>
            <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                <!-- {{ table.filters }} -->
                <li class="flex items-center select-none" v-for="(filters, key) in table.filters" :key="key">
                    <input :id="key + '-filter'" type="checkbox" value=""
                        class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                    />
                    <label :for="key + '-filter'" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ table.columns[key].label }}
                    </label>
                </li>
            </ul>
        </div>
    </div>
</template>