<script setup>
import SvgIcon from "@/Components/Ui/SvgIcon.vue";

const props = defineProps({
    steps: {
        required: true,
        type: Array,
    },
});

const lineActiveClass = (isActive) => {
    return isActive
        ? "text-blue-600 dark:text-blue-500  after:border-blue-100 after:border-4 dark:after:border-blue-800"
        : "after:border-gray-100 after:border-4 dark:after:border-gray-700";
};

const roundActiveClass = (isActive) => {
    return isActive
        ? "bg-blue-100 dark:bg-blue-800"
        : "bg-gray-100 dark:bg-gray-700";
};

const displayClass = (isActive, isLast) => {
    return isLast
        ? lineActiveClass(isActive) +
              " w-full after:content-[''] after:w-full after:h-1 after:border-b after:inline-block"
        : "";
};

// Base:     flex w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:inline-block
// Active:   text-blue-600 dark:text-blue-500  after:border-blue-100 after:border-4 dark:after:border-blue-800
// Inactive: after:border-gray-100 after:border-4 dark:after:border-gray-700
</script>

<template>
    <ol class="flex items-center w-full mb-4 sm:mb-5">
        <li
            v-for="(step, key) in steps"
            class="flex items-center"
            :class="displayClass(step.active, key + 1 != steps.length)"
        >
            <div
                class="flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0"
                :class="roundActiveClass(step.active)"
            >
                <SvgIcon
                    :icon="step.icon"
                    class="text-blue-600 dark:text-blue-300"
                />
            </div>
        </li>
    </ol>
</template>
