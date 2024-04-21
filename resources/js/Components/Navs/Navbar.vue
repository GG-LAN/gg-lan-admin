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
    <nav class="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="p-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    <svg id="toggleSidebarMobileClose" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                    
                    <Link :href="route('dashboard')" class="flex ml-2 md:mr-24">
                        <ApplicationLogo class="h-8 mr-4 bg-gray-800 pr-1"/>
                    </Link>

                    <!-- General Search Field -->
                    <div class="lg-block lg-pl-3.5 mt-1"></div>
                    <!-- <form action="#" method="GET" class="hidden lg:block lg:pl-3.5">
                        <label for="topbar-search" class="sr-only">Rechercher</label>
                        <div class="relative mt-1 lg:w-96">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" name="email" id="topbar-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rechercher...">
                        </div>
                    </form> -->
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
