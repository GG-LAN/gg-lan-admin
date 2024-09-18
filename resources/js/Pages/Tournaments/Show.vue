<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PageTitle from "@/Components/Ui/PageTitle.vue";
import Table from "@/Components/Ui/Table.vue";
import UpdateTournamentInfoForm from "./Partials/UpdateTournamentInfoForm.vue";
import UpdateTournamentDateForm from "./Partials/UpdateTournamentDateForm.vue";
import OpenTournamentForm from "./Partials/OpenTournamentForm.vue";
import UpdateTournamentImage from "./Partials/UpdateTournamentImage.vue";
import DeleteTournament from "./Partials/DeleteTournament.vue";
import Col from "@/Components/Ui/Col.vue";
import NewTable from "@/Components/Ui/Table/NewTable.vue";

defineOptions({ layout: AuthenticatedLayout });
</script>

<template>
    <Head title="Tournois" />
    <PageTitle :breadcrumbs="$page.props.breadcrumbs">
        <h1 class="text-xl font-semibold sm:text-2xl">
            {{ $page.props.tournament.name }}
        </h1>

        <span
            v-if="$page.props.tournament.status == 'open'"
            class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-green-900 dark:text-green-300"
        >
            Ouvert
        </span>
        <span
            v-else-if="$page.props.tournament.status == 'closed'"
            class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-red-900 dark:text-red-300"
        >
            Fermé
        </span>
        <span
            v-else
            class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-yellow-900 dark:text-yellow-300"
        >
            Terminé
        </span>
    </PageTitle>

    <Col
        size="4"
        :background="true"
        v-if="$page.props.tournament.status == 'closed'"
    >
        <OpenTournamentForm :tournament="$page.props.tournament" />
    </Col>

    <!-- Image Display + update image OR Dropzone image -->
    <Col
        size="4"
        :background="true"
        class="lg:col-span-1"
        title="Tournament Cover"
    >
        <UpdateTournamentImage :tournament="$page.props.tournament" />
    </Col>

    <!-- Tournament info -->
    <Col
        size="4"
        :background="true"
        class="lg:col-span-2"
        title="Tournament Info"
    >
        <UpdateTournamentInfoForm />
    </Col>

    <Col
        size="4"
        :background="true"
        class="lg:col-span-1"
        title="Tournament Dates"
    >
        <UpdateTournamentDateForm />
    </Col>

    <Col size="4" class="my-4">
        <span
            class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-gray-400"
        >
            {{ __("Relative Informations") }}
        </span>
    </Col>

    <!-- Tournament Stripe Prices -->
    <Col size="4" class="lg:col-span-2" title="Available Price(s)">
        <NewTable :table="$page.props.tournamentPrices" />
    </Col>

    <!-- Tournament Related Info -->
    <Col
        size="4"
        class="lg:col-span-2"
        title="Registered Teams"
        v-if="$page.props.tournament.type == 'team'"
    >
        <NewTable :table="$page.props.tournamentTeams" />
    </Col>

    <Col size="4" class="lg:col-span-2" title="Registered Players" v-else>
        <NewTable :table="$page.props.tournamentPlayers" />
    </Col>

    <Col size="4" class="lg:col-span-2" title="Online Payments">
        <NewTable :table="$page.props.tournamentPayments" />
    </Col>

    <Col size="4" class="mt-4">
        <DeleteTournament />
    </Col>
</template>
