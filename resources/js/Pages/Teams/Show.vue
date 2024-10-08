<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PageTitle from "@/Components/Ui/PageTitle.vue";
import NewTable from "@/Components/Ui/Table/NewTable.vue";
import RegistrationStateTeam from "./Partials/RegistrationStateTeam.vue";
import TeamImage from "./Partials/TeamImage.vue";
import UpdateTeamForm from "./Partials/UpdateTeamForm.vue";
import Col from "@/Components/Ui/Col.vue";
import DeleteTeam from "./Partials/DeleteTeam.vue";

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

    <Col size="4" class="my-4" title>
        <span
            class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-gray-400"
        >
            {{ __("Relative Informations") }}
        </span>
    </Col>

    <!-- Players -->
    <Col size="4" title="Team members">
        <NewTable :table="$page.props.teamPlayers" />
    </Col>

    <Col size="4" class="mt-4">
        <DeleteTeam />
    </Col>
</template>
