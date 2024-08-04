<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import Table from '@/Components/Ui/Table.vue';
import RegistrationStateTeam from './Partials/RegistrationStateTeam.vue';
import TeamImage from './Partials/TeamImage.vue';
import UpdateTeamForm from './Partials/UpdateTeamForm.vue';

defineOptions({layout: AuthenticatedLayout});
</script>

<template>
    <Head title="Équipes" />
    <PageTitle :breadcrumbs="$page.props.breadcrumbs">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
            {{ $page.props.team.name }}
        </h1>

        <RegistrationStateTeam :registrationState="$page.props.team.registration_state"/>
    </PageTitle>
    
    <!-- Team Image Display -->
    <div class="col-span-4 lg:col-span-1">
        <TeamImage/>
    </div>

    <!-- Tournament info -->
    <div class="col-span-4 lg:col-span-3 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <UpdateTeamForm :team="$page.props.team"/>
    </div>

    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white col-span-4 mt-4 mb-4">
        Informations Relatives
    </h1>

    <!-- Players -->
    <div class="col-span-4 lg:col-span-3 p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Joueurs</h2>
        <Table :rows="$page.props.playersData" :rowsInfo="$page.props.playersRowsInfo" :route="route('teams.show', $page.props.team.id)"/>
    </div>    
</template>
