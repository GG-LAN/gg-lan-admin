<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import Table from '@/Components/Ui/Table.vue';
import UpdateTournamentInfoForm from './Partials/UpdateTournamentInfoForm.vue';
import UpdateTournamentDateForm from './Partials/UpdateTournamentDateForm.vue';
import UpdateTournamentGameForm from './Partials/UpdateTournamentGameForm.vue';

defineOptions({layout: AuthenticatedLayout});
</script>

<template>
    <Head title="Tournois" />
    <PageTitle :title="$page.props.tournament.name" :breadcrumbs="$page.props.breadcrumbs"/>

    <!-- Tournament info -->
    <div class="col-span-2 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <UpdateTournamentInfoForm :tournament="$page.props.tournament" :status="$page.props.status"/>
    </div>

    <div class="col-span-1 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <UpdateTournamentDateForm :tournament="$page.props.tournament"/>
    </div>

    <div class="col-span-1 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <UpdateTournamentGameForm :tournament="$page.props.tournament" :games="$page.props.games" :types="$page.props.types"/>
    </div>

    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white col-span-4 mt-4 mb-4">
        Informations Relatives
    </h1>

    <!-- Tournament Stripe Prices -->
    <div class="col-span-2 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Prix disponible(s)</h2>
        <Table :rows="$page.props.pricesData" :rowsInfo="$page.props.pricesRowsInfo" :route="route('tournaments.show', $page.props.tournament.id)"/>
    </div>
    
    <!-- Tournament Related Info -->
    <!-- <div v-if="$page.props.tournament.type == 'team'" class="col-span-2 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Équipe(s) inscrite(s)</h2>
        <Table :rows="$page.props.teamsData" :rowsInfo="$page.props.teamsRowsInfo" :route="route('tournaments.show', $page.props.tournament.id)"/>
    </div> -->

    <!-- <div v-else class="col-span-2 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">            
        <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Joueur(s) inscrit(s)</h2>
        <Table :rows="$page.props.soloTournamentsData" :rowsInfo="$page.props.soloTournamentsRowsInfo" :route="route('players.show', $page.props.player.id)"/>
    </div> -->
    <div class="col-span-3">
    </div>
</template>
