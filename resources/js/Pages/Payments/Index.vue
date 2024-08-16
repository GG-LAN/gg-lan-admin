<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import NewTable from '@/Components/Ui/Table/NewTable.vue';

defineOptions({layout: AuthenticatedLayout});
</script>

<template>
    <Head title="Paiements" />
    <PageTitle title="Paiements" :breadcrumbs="$page.props.breadcrumbs"/>

    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white col-span-4">
        <NewTable :table="$page.props.table">>
        </NewTable>
    </div>
</template>
