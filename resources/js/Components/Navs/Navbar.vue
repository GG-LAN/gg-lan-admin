<script setup>
import { Link, router } from '@inertiajs/vue3';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import ApplicationLogo from '@/Components/Ui/ApplicationLogo.vue';
import DarkModeButton from '@/Components/Ui/DarkModeButton.vue';

const props = defineProps({});

const logout = () => {
    router.post("/logout");
}
</script>

<template>
    <nav class="fixed z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button" class="inline-flex items-center p-2 ms-3 mr-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 z-40">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    
                    <Link :href="route('dashboard')" class="flex ml-2 md:mr-24">
                        <ApplicationLogo class="h-8 mr-4 bg-gray-800 pr-1"/>
                    </Link>

                    <!-- General Search Field -->
                    <div class="lg-block lg-pl-3.5 mt-1"></div>
                </div>

                <div class="flex items-center">

                    <!-- Toggle Dark/Light mode -->
                    <DarkModeButton/>

                    <!-- Profile -->
                    <div class="flex items-center ml-3 gap-4">
                        <!-- Avatar button -->
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-profile">
                            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-profile">
                            <!-- Profile info -->
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    {{ $page.props.auth.user.name }}
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    {{ $page.props.auth.user.email }}
                                </p>
                            </div>

                            <!-- Links -->
                            <ul class="py-1" role="none">
                                <li>
                                    <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                                        Profil
                                    </Link>
                                </li>
                                <li>
                                    <form @submit.prevent="logout" class="block hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <button type="submit" class="px-4 py-2 text-sm text-gray-700  dark:text-gray-300 dark:hover:text-white">
                                            Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
