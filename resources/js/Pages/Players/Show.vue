<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import Table from '@/Components/Ui/Table.vue';
import UpdatePlayerForm from './Partials/UpdatePlayerForm.vue';

defineOptions({layout: AuthenticatedLayout});
</script>

<template>
    <Head title="Joueurs" />
    <PageTitle :title="$page.props.player.pseudo" :breadcrumbs="$page.props.breadcrumbs"/>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-4 lg-col-span-1">
        <UpdatePlayerForm :player="$page.props.player"/>
    </div>
    
    <div class="col-span-4 grid grid-cols-4 gap-4">
        <div class="col-span-4 lg:col-span-2 sm:col-span-4 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Équipes</h2>
            <Table :rows="$page.props.teamsData" :rowsInfo="$page.props.teamsRowsInfo" :route="route('players.show', $page.props.player.id)"/>
        </div>

        <div class="col-span-4 lg:col-span-2 sm:col-span-4 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">            
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Tournois solo</h2>
            <Table :rows="$page.props.soloTournamentsData" :rowsInfo="$page.props.soloTournamentsRowsInfo" :route="route('players.show', $page.props.player.id)"/>
        </div>
    </div>
</template>
