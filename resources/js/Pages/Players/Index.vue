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
    <Head title="Joueurs" />
    <PageTitle title="Joueurs" :breadcrumbs="$page.props.breadcrumbs"/>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-4">

        <Table :rows="$page.props.tableData" :rowsInfo="$page.props.tableRowsInfo" :route="route('players.index')">
            <template #drawerCreate="{ drawer , uid}">
                <DrawerCreate title="Ajouter un joueur" :drawer="drawer" :uid="uid"/>
            </template>
            <template #drawerUpdate="{ modelId, drawer, uid }">
                <DrawerUpdate title="Modifier le joueur" :modelId="modelId" :drawer="drawer" :uid="uid"/>
            </template>
            <template #drawerDelete="{ modelId, drawer, uid }">
                <DrawerDelete title="Supprimer le joueur" :modelId="modelId" :drawer="drawer" :uid="uid"/>
            </template>
        </Table>
        
    </div>
</template>
