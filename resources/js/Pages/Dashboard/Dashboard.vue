<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
// import Table from '@/Components/Ui/Table.vue'
import PageTitle from "@/Components/Ui/PageTitle.vue";
import PlayersStat from "./Partials/PlayersStat.vue";
import TeamsStat from "./Partials/TeamsStat.vue";
import PurchasedPlacesStat from "./Partials/PurchasedPlacesStat.vue";
import TournamentsFilling from "./Partials/TournamentsFilling.vue";
// import Chart from '@/Components/Ui/Chart.vue';
import Col from "@/Components/Ui/Col.vue";

defineOptions({ layout: AuthenticatedLayout });
</script>

<template>
    <Head title="Dashboard" />
    <PageTitle title="Dashboard" />

    <!-- Stats Boxes -->
    <Col size="4" class="grid grid-cols-4 gap-4 mb-4">
        <PlayersStat
            class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"
        />

        <TeamsStat
            class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"
        />

        <PurchasedPlacesStat
            class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"
        />
    </Col>

    <!-- Content Boxes -->
    <TournamentsFilling
        class="col-span-4 lg:col-span-1 md:col-span-2 sm:col-span-4"
    />

    <Col size="4" :background="true" class="text-center">
        <p>De nouvelles fonctionnalités arriveront bientôt...</p>
        <p>Vous pouvez toujours me contacter pour proposer des idées.</p>
    </Col>
</template>
