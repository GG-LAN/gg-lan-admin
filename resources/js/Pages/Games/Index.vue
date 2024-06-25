<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import Table from '@/Components/Ui/Table.vue';
import DrawerCreate from './Partials/DrawerCreate.vue';
import DrawerUpdate from './Partials/DrawerUpdate.vue';
import DrawerDelete from './Partials/DrawerDelete.vue';

defineOptions({layout: AuthenticatedLayout});
</script>

<template>
    <Head title="Jeux" />
    <PageTitle title="Jeux" :breadcrumbs="$page.props.breadcrumbs"/>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-4">

        <Table :rows="$page.props.tableData" :rowsInfo="$page.props.tableRowsInfo" :route="route('games.index')">
            <template #drawerCreate="{ drawer }">
                <DrawerCreate title="Ajouter un jeu" :drawer="drawer"/>
            </template>
            <template #drawerUpdate="{ modelId, drawer }">
                <DrawerUpdate title="Modifier le jeu" :modelId="modelId" :drawer="drawer"/>
            </template>
            <template #drawerDelete="{ modelId, drawer }">
                <DrawerDelete title="Supprimer le jeu" :modelId="modelId" :drawer="drawer"/>
            </template>
        </Table>
        
    </div>
</template>
