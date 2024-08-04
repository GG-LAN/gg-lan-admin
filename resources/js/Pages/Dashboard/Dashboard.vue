<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Table from '@/Components/Ui/Table.vue'
import PageTitle from '@/Components/Ui/PageTitle.vue';
import PlayersStat from './Partials/PlayersStat.vue';
import StatCard from '@/Components/Ui/StatCard.vue';

defineOptions({layout: AuthenticatedLayout});

</script>

<template>
    <Head title="Dashboard" />
    <PageTitle title="Dashboard" />

    <!-- Stats Boxes -->
    <div class="grid grid-cols-4 gap-4 col-span-4">
        <PlayersStat/>

        <StatCard class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4" icon="users" color="yellow" title="Equipes" data="24"/>

        <StatCard class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4" icon="trophy" color="red" title="Remplissage des tournois" data="59 %"/>

        <StatCard class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4" icon="money-bill" color="green" title="Paiements en ligne" data="589"/>
    </div>

    <!-- Content Boxes -->
    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-4">
    </div>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-2">
    </div>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-2">
    </div>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-1">
    </div>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-3">
    </div>
</template>
