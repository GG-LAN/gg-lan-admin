<script setup>
import SvgIcon from '@/Components/Ui/SvgIcon.vue';

const props = defineProps({
    rowInfo: {
        type: Object,
        required: true
    },
    row: {
        type: Object,
        required: true
    },
    rowKey: {
        type: String,
        required: true
    }
})
</script>

<template>
    <div class="flex items-center" v-if="row[rowKey]">
        <div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></div>
        {{ rowInfo.label_true }}
    </div>
    <div class="flex items-center" v-else>
        <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
        {{ rowInfo.label_false }}
    </div>
</template>