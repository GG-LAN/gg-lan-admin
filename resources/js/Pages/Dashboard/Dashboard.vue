<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
// import Table from '@/Components/Ui/Table.vue'
import PageTitle from '@/Components/Ui/PageTitle.vue';
import PlayersStat from './Partials/PlayersStat.vue';
import TeamsStat from './Partials/TeamsStat.vue';
import PurchasedPlacesStat from './Partials/PurchasedPlacesStat.vue';
import TournamentsFilling from './Partials/TournamentsFilling.vue';
// import Chart from '@/Components/Ui/Chart.vue';

defineOptions({layout: AuthenticatedLayout});

</script>

<template>
    <Head title="Dashboard" />
    <PageTitle title="Dashboard" />

    <!-- Stats Boxes -->
    <div class="grid grid-cols-4 gap-4 col-span-4 mb-4">
        <PlayersStat class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"/>

        <TeamsStat class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"/>

        <PurchasedPlacesStat class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"/>

        <!-- <TournamentsFilling class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"/> -->
    </div>

    <!-- Content Boxes -->
    <!-- <Chart id="test" class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"/> -->
    <TournamentsFilling class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"/>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-gray-200 col-span-4 text-center">
        <p>De nouvelles fonctionnalités arriveront bientôt...</p>
        <p>Vous pouvez toujours me contacter pour proposer des idées.</p>
    </div>
</template>
