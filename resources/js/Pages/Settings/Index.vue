<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Table from '@/Components/Ui/Table.vue'
import PageTitle from '@/Components/Ui/PageTitle.vue';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import Tabs from '@/Components/Ui/Tabs.vue';
import TabsVertical from '@/Components/Ui/TabsVertical.vue';
// import StripeForm from './Partials/StripeForm.vue';
import RulesForm from './Partials/RulesForm.vue';

defineOptions({layout: AuthenticatedLayout});

let tabs = [
    // {
    //     id: "stripe",
    //     label: "Stripe",
    //     icon: "stripe-s",
    //     library: "fab"
    // },
    {
        id: "rules",
        label: "Règlement",
        icon: "align-left"
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
        <!-- <template #stripe>
            <StripeForm/>
        </template> -->
        <template #rules>
            <RulesForm/>
        </template>
    </TabsVertical>
</template>
