<script setup>
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import { ref } from 'vue';

const props = defineProps({
    tabId: {
        type: String,
        required: true
    },
    tabs: {
        type: Array,
        required: true
    }
})

let activeTab = ref(props.tabs[0].id);

const changeActive = id => {
    activeTab.value = id;
}
</script>

<template>
    <ul class="flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0" :id="props.tabId + '-tab'">
        <li class="me-2" role="presentation" v-for="tab in props.tabs">
            <button
                @click="changeActive(tab.id)"
                type="button"
                :id="tab.id + '-tab'"
                class="inline-flex items-center px-4 py-3 rounded-lg w-full"
                :class="{
                    'text-white bg-blue-700 dark:bg-blue-600 active':(activeTab == tab.id),
                    'hover:text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white':(activeTab != tab.id),
                }"
            >
                <SvgIcon :icon="tab.icon" v-if="tab.icon" :library="tab.library" class="w-4 h-4 me-2"/>
                {{ tab.label }}
            </button>
        </li>
    </ul>

    <div :id="props.tabId + '-tab-content'" class="p-6 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-3">
        <div class="bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full" v-show="activeTab == tab.id" :id="tab.id" v-for="tab in props.tabs">            
            <slot :name="tab.id"/>
            <slot/>
        </div>
    </div>
</template>