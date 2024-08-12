<script setup>

const props = defineProps({
    checkboxId: {
        type: String,
        required: true
    },
    row: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="flex items-center">
        <input 
            :id="props.checkboxId + '-' + props.row.id"
            type="checkbox"
            class="checkAll w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
        >

        <label
            :for="props.checkboxId + '-' + props.row.id" 
            class="sr-only"
        >
            checkbox
        </label>
    </div>
</template>