<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Table from '@/Components/Ui/Table.vue'
import PageTitle from '@/Components/Ui/PageTitle.vue';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import Tabs from '@/Components/Ui/Tabs.vue';
import TabsVertical from '@/Components/Ui/TabsVertical.vue';
import StripeForm from './Partials/StripeForm.vue';

defineOptions({layout: AuthenticatedLayout});

let tabs = [
    // {
    //     id: "stripe",
    //     label: "Stripe",
    //     icon: "stripe-s",
    //     library: "fab"
    // },
    // {
    //     id: "rules",
    //     label: "Règlement",
    //     icon: "align-left"
    // },
    // {
    //     id: "cron",
    //     label: "Jobs",
    //     icon: "clock-rotate-left"
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
        </template>
        <template #rules>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Règlement Tab</h3>
            <p class="mb-2">This is some placeholder content the Profile tab's associated content, clicking another tab will toggle the visibility of this one for the next.</p>
            <p>The tab JavaScript swaps classes to control the content visibility and styling.</p>
        </template>
        <template #settings>
        </template> -->
    </TabsVertical>
</template>
