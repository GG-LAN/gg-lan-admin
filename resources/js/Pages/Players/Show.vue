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

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-1">
        <UpdatePlayerForm :player="$page.props.player"/>
    </div>
    
    <div class="col-span-3">
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white mb-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Equipes</h2>
            <Table :rows="$page.props.teamsData" :rowsInfo="$page.props.teamsRowsInfo" :route="route('players.show', $page.props.player.id)"/>
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">            
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tournois solo</h2>
            <Table :rows="$page.props.soloTournamentsData" :rowsInfo="$page.props.soloTournamentsRowsInfo" :route="route('players.show', $page.props.player.id)"/>
        </div>
    </div>
</template>
