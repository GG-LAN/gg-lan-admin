<script setup>
import SvgIcon from '@/Components/Ui/SvgIcon.vue';

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
</script>

<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" :id="props.tabId + '-tab'" :data-tabs-toggle="'#' + props.tabId + '-tab-content-'" role="tablist">
            <li class="me-2" role="presentation" v-for="tab in props.tabs">
                <button
                    :id="tab.id + '-tab'"
                    type="button"
                    class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                    :data-tabs-target="'#' + tab.id"
                    role="tab"
                    :aria-controls="tab.id"
                    aria-selected="false"
                >
                    <SvgIcon :icon="tab.icon" v-if="tab.icon" class="w-4 h-4 me-2"/>
                    {{ tab.label }}
                </button>
            </li>
        </ul>
    </div>

    <div :id="props.tabId + '-tab-content'">
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" :id="tab.id" role="tabpanel" :aria-labelledby="tab.id + '-tab'" v-for="tab in props.tabs">            
            <slot :name="tab.id"/>
            <slot/>
        </div>
    </div>
</template>