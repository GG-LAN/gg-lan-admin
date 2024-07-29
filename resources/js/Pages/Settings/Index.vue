<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import TabsVertical from '@/Components/Ui/TabsVertical.vue';
import StripeForm from './Partials/StripeForm.vue';
import RulesForm from './Partials/RulesForm.vue';

defineOptions({layout: AuthenticatedLayout});

let tabs = [
    {
        id: "rules",
        label: "Règlement",
        icon: "align-left"
    },
    {
        id: "stripe",
        label: "Stripe",
        icon: "stripe-s",
        library: "fab"
    },
    // {
    //     id: "cron",
    //     label: "Jobs",
    //     icon: "clock-rotate-left"
    // },
    // {
    //     id: "log",
    //     label: "Logs",
    //     icon: "book",
    // },
    // {
    //     id: "settings",
    //     label: "Paramètres",
    //     icon: "cog"
    // },
]

</script>

<template>
    <Head title="Paramètres" />
    <PageTitle title="Paramètres" />
    
    <TabsVertical tabId="settingsTabs" :tabs="tabs">
        <template #rules>
            <RulesForm/>
        </template>
        <template #stripe>
            <StripeForm/>
        </template>
    </TabsVertical>
</template>
