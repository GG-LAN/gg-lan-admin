<script setup>
const props = defineProps({
    column: {
        type: Object,
        required: true
    },
    value: {
        // type: Number|Boolean,
        required: true
    }
})
</script>

<template>
    <div class="flex items-center" v-if="value">
        <div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></div>
        {{ column.label_true }}
    </div>
    <div class="flex items-center" v-else>
        <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
        {{ column.label_false }}
    </div>
</template>