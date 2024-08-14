<script setup>
// import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import { computed } from 'vue';

const props = defineProps({
    column: {
        type: Object,
        required: true
    },
    value: {
        type: String,
        required: true
    }
})

const badgeColor = computed(() => {
    let configuration = props.column.badges.find(
        element => element.value == props.value
    );    

    let badgeClasses = "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300";

    switch (configuration.color) {
        case "red":
            badgeClasses = "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300";
        break;
        
        case "orange":
            badgeClasses = "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300";
        break;
            
        case "green":
            badgeClasses = "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300";
        break;
    
        default:
            break;
    }

    return badgeClasses;
});

const textDisplay = computed(() => {
    let configuration = props.column.badges.find(
        element => element.value == props.value
    );

    return configuration.label;
})

</script>

<template>
    <div class="flex items-center">
        <span class=" text-sm font-medium me-2 px-2.5 py-0.5 rounded-full" :class="badgeColor">
            {{ textDisplay }}
        </span>
    </div>
</template>