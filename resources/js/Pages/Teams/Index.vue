<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PageTitle from "@/Components/Ui/PageTitle.vue";
import NewTable from "@/Components/Ui/Table/NewTable.vue";
// import DrawerCreate from './Partials/DrawerCreate.vue';
// import DrawerDelete from './Partials/DrawerDelete.vue';
import Col from "@/Components/Ui/Col.vue";

defineOptions({ layout: AuthenticatedLayout });
</script>

<template>
    <Head title="Équipes" />
    <PageTitle title="Équipes" :breadcrumbs="$page.props.breadcrumbs" />

    <Col size="4">
        <NewTable :table="$page.props.table">
            <!-- <template #drawerCreate="{ drawer, uid }">
                <DrawerCreate title="Ajouter un tournois" :drawer="drawer" :uid="uid"/>
            </template> -->
            <!-- <template #drawerDelete="{ modelId, drawer, uid }">
                <DrawerDelete title="Supprimer le tournois" :modelId="modelId" :drawer="drawer" :uid="uid"/>
            </template> -->
        </NewTable>
    </Col>
</template>
