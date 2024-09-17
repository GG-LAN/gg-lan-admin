<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PageTitle from "@/Components/Ui/PageTitle.vue";
import NewTable from "@/Components/Ui/Table/NewTable.vue";
import RegistrationStateTeam from "./Partials/RegistrationStateTeam.vue";
import TeamImage from "./Partials/TeamImage.vue";
import UpdateTeamForm from "./Partials/UpdateTeamForm.vue";
import Col from "@/Components/Ui/Col.vue";

defineOptions({ layout: AuthenticatedLayout });
</script>

<template>
    <Head title="Équipes" />
    <PageTitle :breadcrumbs="$page.props.breadcrumbs">
        <h1
            class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-gray-400"
        >
            {{ $page.props.team.name }}
        </h1>

        <RegistrationStateTeam
            :registrationState="$page.props.team.registration_state"
        />
    </PageTitle>

    <!-- Team Image Display -->
    <Col
        size="4"
        :background="true"
        class="lg:col-span-1 flex justify-center items-center"
    >
        <TeamImage />
    </Col>

    <!-- Tournament info -->
    <Col size="4" :background="true" class="lg:col-span-3" title="Team Info">
        <UpdateTeamForm :team="$page.props.team" />
    </Col>

    <h1
        class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-gray-400 col-span-4 mt-4 mb-4"
    >
        {{ __("Relative Informations") }}
    </h1>

    <!-- Players -->
    <Col size="4" title="Team members">
        <NewTable :table="$page.props.teamPlayers" />
    </Col>
</template>
