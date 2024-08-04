<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import Table from '@/Components/Ui/Table.vue';
import UpdateTournamentInfoForm from './Partials/UpdateTournamentInfoForm.vue';
import UpdateTournamentDateForm from './Partials/UpdateTournamentDateForm.vue';
import OpenTournamentForm from './Partials/OpenTournamentForm.vue';
import UpdateTournamentImage from './Partials/UpdateTournamentImage.vue';
import DeleteTournament from './Partials/DeleteTournament.vue';

defineOptions({layout: AuthenticatedLayout});
</script>

<template>
    <Head title="Tournois" />
    <PageTitle :breadcrumbs="$page.props.breadcrumbs">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
            {{ $page.props.tournament.name }}
        </h1>

        <span v-if="$page.props.tournament.status == 'open'" class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-green-900 dark:text-green-300" >
            Ouvert
        </span>
        <span v-else-if="$page.props.tournament.status == 'closed'" class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-red-900 dark:text-red-300">
            Fermé
        </span>
        <span v-else class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
            Terminé
        </span>
    </PageTitle>

    <div v-if="$page.props.tournament.status == 'closed'" class="col-span-4 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <OpenTournamentForm :tournament="$page.props.tournament"/>
    </div>
    
    <!-- Image Display + update image OR Dropzone image -->
    <div class="col-span-4 lg:col-span-1">
        <UpdateTournamentImage :tournament="$page.props.tournament"/>
    </div>

    <!-- Tournament info -->
    <div class="col-span-4 lg:col-span-2 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <UpdateTournamentInfoForm :tournament="$page.props.tournament" :games="$page.props.games"/>
    </div>

    <div class="col-span-4 lg:col-span-1 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <UpdateTournamentDateForm :tournament="$page.props.tournament"/>
    </div>

    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white col-span-4 mt-4 mb-4">
        Informations Relatives
    </h1>

    <!-- Tournament Stripe Prices -->
    <div class="col-span-4 lg:col-span-2 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Prix disponible(s)</h2>
        <Table :rows="$page.props.pricesData" :rowsInfo="$page.props.pricesRowsInfo" :route="route('tournaments.show', $page.props.tournament.id)"/>
    </div>
    
    <!-- Tournament Related Info -->
    <div v-if="$page.props.tournament.type == 'team'" class="col-span-4 lg:col-span-2 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Équipes inscrites</h2>
        <Table :rows="$page.props.teamsData" :rowsInfo="$page.props.teamsRowsInfo" :route="route('tournaments.show', $page.props.tournament.id)"/>
    </div>

    <div v-else class="col-span-4 lg:col-span-2 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">            
        <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Joueurs inscrits</h2>
        <Table :rows="$page.props.playersData" :rowsInfo="$page.props.playersRowsInfo" :route="route('tournaments.show', $page.props.tournament.id)"/>
    </div>

    <div class="col-span-4 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <DeleteTournament :tournament="$page.props.tournament"/>
    </div>
</template>
