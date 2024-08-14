<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import NewTable from '@/Components/Ui/Table/NewTable.vue';
import DrawerCreate from './Partials/DrawerCreate.vue';
import DrawerDelete from './Partials/DrawerDelete.vue';
import DrawerUpdate from './Partials/DrawerUpdate.vue';

defineOptions({layout: AuthenticatedLayout});
</script>

<template>
    <Head title="FAQ" />
    <PageTitle title="FAQ" :breadcrumbs="$page.props.breadcrumbs"/>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-4">

        <NewTable :table="$page.props.table">
            <template #drawerCreate="{ drawer, uid }">
                <DrawerCreate title="Ajouter une question" :drawer="drawer" :uid="uid"/>
            </template>
            <template #drawerUpdate="{ modelId, drawer, uid }">
                <DrawerUpdate title="Modifier une question" :modelId="modelId" :drawer="drawer" :uid="uid"/>
            </template>
            <template #drawerDelete="{ modelId, drawer, uid }">
                <DrawerDelete title="Supprimer une question" :modelId="modelId" :drawer="drawer" :uid="uid"/>
            </template>
        </NewTable>
        
    </div>
</template>
