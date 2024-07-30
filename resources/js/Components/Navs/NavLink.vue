<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';

const props = defineProps({
    routename: {
        type: String,
        required: true,
    },
    icon: {
        type: String
    },
    iconColor: {
        type: String
    },
    activeFor: {
        type: String,
        required: true
    }
});

const page = usePage();

const linkClasses = computed(() =>
    page.component.includes(props.activeFor)
    ? 'bg-gray-100 dark:bg-gray-700 ease-in-out'
    : 'transition duration-150 ease-in-out'
);

const iconClasses = computed(() => {
    let classes = "text-gray-500 group-hover:text-gray-900   dark:text-gray-400 dark:group-hover:text-gray-200";

    if (props.iconColor) {
        switch (props.iconColor) {
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
</script>

<template>
    <li>
        <Link :href="route(routename)" :class="linkClasses" class="flex items-center p-2 rounded-lg text-base text-gray-900 hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
            <SvgIcon class="w-5 h-5" :class="iconClasses" :icon="icon" v-if="icon"/>
            <span class="ml-3">
                <slot />
            </span>
        </Link>
    </li>
</template>
