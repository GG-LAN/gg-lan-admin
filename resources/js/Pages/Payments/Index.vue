<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PageTitle from "@/Components/Ui/PageTitle.vue";
import NewTable from "@/Components/Ui/Table/NewTable.vue";

defineOptions({ layout: AuthenticatedLayout });
</script>

<template>
    <Head title="Paiements" />
    <PageTitle title="Paiements" :breadcrumbs="$page.props.breadcrumbs" />

    <div class="col-span-4">
        <NewTable :table="$page.props.table" />
    </div>
</template>
