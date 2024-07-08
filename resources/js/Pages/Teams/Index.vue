<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import Table from '@/Components/Ui/Table.vue';
// import DrawerCreate from './Partials/DrawerCreate.vue';
// import DrawerDelete from './Partials/DrawerDelete.vue';

defineOptions({layout: AuthenticatedLayout});
</script>

<template>
    <Head title="Équipes" />
    <PageTitle title="Équipes" :breadcrumbs="$page.props.breadcrumbs"/>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-4">

        <Table :rows="$page.props.tableData" :rowsInfo="$page.props.tableRowsInfo" :route="route('teams.index')">
            <!-- <template #drawerCreate="{ drawer, uid }">
                <DrawerCreate title="Ajouter un tournois" :drawer="drawer" :uid="uid"/>
            </template> -->
            <!-- <template #drawerDelete="{ modelId, drawer, uid }">
                <DrawerDelete title="Supprimer le tournois" :modelId="modelId" :drawer="drawer" :uid="uid"/>
            </template> -->
        </Table>
        
    </div>
</template>
