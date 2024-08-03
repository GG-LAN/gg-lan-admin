<script setup>
import NavLink from '@/Components/Navs/NavLink.vue';
import DropdownNavLink from '@/Components/Navs/DropdownNavLink.vue';
</script>

<template>
    <aside id="sidebar" class="fixed top-0 left-0 z-40 flex flex-col flex-shrink-0 w-64 h-full pt-16 font-normal duration-75 lg:flex transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <ul class="pb-2 space-y-2">
                        <NavLink routename="dashboard" icon="chart-column" iconColor="purple" activeFor="Dashboard">
                            Dashboard
                        </NavLink>

                        <NavLink routename="players.index" icon="user" iconColor="blue" activeFor="Players">
                            Joueurs
                        </NavLink>

                        <NavLink routename="teams.index" icon="users" iconColor="yellow" activeFor="Teams">
                            Équipes
                        </NavLink>

                        <NavLink routename="tournaments.index" icon="trophy" iconColor="red" activeFor="Tournaments">
                            Tournois
                        </NavLink>

                        <NavLink routename="payments.index" icon="money-bill" iconColor="green" activeFor="Payments">
                            Paiements
                        </NavLink>
                        
                        <NavLink routename="games.index" icon="gamepad" iconColor="gray" activeFor="Games">
                            Jeux
                        </NavLink>

                        <NavLink routename="faqs.index" icon="question" iconColor="purple" activeFor="Faqs">
                            FAQ
                        </NavLink>

                        <NavLink routename="settings.index" icon="cog" iconColor="gray" activeFor="Settings">
                            Paramètres
                        </NavLink>
                    </ul>
                </div>
            </div>
        </div>
    </aside>

    <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>
</template>
