<script setup>
import { onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';

const toggleMode = () => {
    const pageElement = document.documentElement;

    const darkIcon = document.querySelector("#theme-toggle-dark-icon");
    const lightIcon = document.querySelector("#theme-toggle-light-icon");

    darkIcon.classList.toggle("hidden");
    lightIcon.classList.toggle("hidden");

    if (pageElement.classList.contains("dark")) {
        pageElement.classList.remove("dark");
        pageElement.classList.add("light")

        localStorage.setItem("color-theme", "light");
    }
    else {
        pageElement.classList.remove("light");
        pageElement.classList.add("dark")

        localStorage.setItem("color-theme", "dark");
    }
}

onMounted(() => {
    const colorTheme = localStorage.getItem("color-theme");
    
    const pageElement = document.documentElement;

    const darkIcon = document.querySelector("#theme-toggle-dark-icon");
    const lightIcon = document.querySelector("#theme-toggle-light-icon");

    if (colorTheme) {
        // Set current class theme
        colorTheme == "dark" ? pageElement.classList.add("dark") : pageElement.classList.remove("dark");

        // Display icon of current theme
        colorTheme == "dark" ? lightIcon.classList.toggle("hidden") : darkIcon.classList.toggle("hidden");
    }
    else {
        if (window.matchMedia) {
            // Set current class theme
            window.matchMedia('(prefers-color-scheme: dark)') ? pageElement.classList.add("dark") : pageElement.classList.remove("dark");

            // Display icon of current theme
            window.matchMedia('(prefers-color-scheme: dark)') ? lightIcon.classList.toggle("hidden") : darkIcon.classList.toggle("hidden");
        }
    }
});
</script>

<template>
    <button @click="toggleMode" id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 inline-flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
        <SvgIcon id="theme-toggle-dark-icon" icon="moon" class="hidden w-5 h-5"/>
        <SvgIcon id="theme-toggle-light-icon" icon="sun" class="hidden w-5 h-5"/>
    </button>
    <div id="tooltip-toggle" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
        Toggle dark mode
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
</template>
