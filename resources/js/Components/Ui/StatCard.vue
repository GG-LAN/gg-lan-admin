<script setup>
import { onMounted, computed } from 'vue'
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import axios from 'axios';

const props = defineProps({
    icon: {
        type: String
    },
    color: {
        type: String,
        default: "gray"
    },
    title: {
        type: String
    },
    data: {
        default: "..."
    }
});

onMounted(() => {
});

const iconClasses = computed(() => {
    let classes = "";

    if (props.color) {
        switch (props.color) {
            case "blue":
                classes = "text-blue-500 group-hover:text-blue-900   dark:text-blue-400 dark:group-hover:text-blue-200";
            break;

            case "red":
                classes = "text-red-500 group-hover:text-red-900   dark:text-red-400 dark:group-hover:text-red-200";
            break;

            case "green":
                classes = "text-green-500 group-hover:text-green-900   dark:text-green-400 dark:group-hover:text-green-200";
            break;

            case "gray":
                classes = "text-gray-500 group-hover:text-gray-900   dark:text-gray-400 dark:group-hover:text-gray-200";
            break;

            case "indigo":
                classes = "text-indigo-500 group-hover:text-indigo-900   dark:text-indigo-400 dark:group-hover:text-indigo-200";
            break;

            case "purple":
                classes = "text-purple-500 group-hover:text-purple-900   dark:text-purple-400 dark:group-hover:text-purple-200";
            break;

            case "yellow":
                classes = "text-yellow-500 group-hover:text-yellow-900   dark:text-yellow-400 dark:group-hover:text-yellow-200";
            break;
        
            default:
            break;
        }
    }

    return classes;
});

const bgClasses = computed(() => {
    let classes = "";

    if (props.color) {
        switch (props.color) {
            case "blue":
                classes = "bg-gradient-to-tr from-transparent dark:from-transparent to-blue-400 dark:to-blue-900";
            break;

            case "red":
                classes = "bg-gradient-to-tr from-transparent dark:from-transparent to-red-400 dark:to-red-900";
            break;

            case "green":
                classes = "bg-gradient-to-tr from-transparent dark:from-transparent to-green-400 dark:to-green-900";
            break;

            case "gray":
                classes = "bg-gradient-to-tr from-transparent dark:from-transparent to-gray-400 dark:to-gray-900";
            break;

            case "indigo":
                classes = "bg-gradient-to-tr from-transparent dark:from-transparent to-indigo-400 dark:to-indigo-900";
            break;

            case "purple":
                classes = "bg-gradient-to-tr from-transparent dark:from-transparent to-purple-400 dark:to-purple-900";
            break;

            case "yellow":
                classes = "bg-gradient-to-tr from-transparent dark:from-transparent to-yellow-400 dark:to-yellow-900";
            break;
        
            default:
            break;
        }
    }

    return classes;
});

</script>

<template>
    <div class="p-4 rounded-lg sm:p-6 text-gray-900 dark:text-gray-200" :class="bgClasses">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-center" :class="iconClasses">
                <SvgIcon class="h-16 w-16" :icon="props.icon" />
            </div>
            <div class="text-right">
                <div class="uppercase font-medium ">{{ props.title }}</div>
                <div class="text-2xl font-bold">{{ props.data }}</div>
            </div>
        </div>
    </div>
</template>