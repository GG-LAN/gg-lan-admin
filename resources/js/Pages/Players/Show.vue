<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PageTitle from "@/Components/Ui/PageTitle.vue";
import Table from "@/Components/Ui/Table.vue";
import UpdatePlayerForm from "./Partials/UpdatePlayerForm.vue";
import Col from "@/Components/Ui/Col.vue";
import NewTable from "@/Components/Ui/Table/NewTable.vue";

defineOptions({ layout: AuthenticatedLayout });
</script>

<template>
    <Head title="Joueurs" />
    <PageTitle
        :title="$page.props.player.pseudo"
        :breadcrumbs="$page.props.breadcrumbs"
    />

    <Col size="4" :background="true" class="lg-col-span-1" title="Player info">
        <UpdatePlayerForm :player="$page.props.player" />
    </Col>

    <div class="col-span-4 grid grid-cols-4 gap-4">
        <Col size="2" title="Teams">
            <NewTable :table="$page.props.playerTeams" />
        </Col>

        <Col size="2" title="Solo tournaments">
            <NewTable :table="$page.props.playerTournaments" />
        </Col>
    </div>
</template>
