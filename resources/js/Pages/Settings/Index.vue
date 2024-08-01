<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import TabsVertical from '@/Components/Ui/TabsVertical.vue';
import StripeForm from './Partials/StripeForm.vue';
import RulesForm from './Partials/RulesForm.vue';
import LocationForm from './Partials/LocationForm.vue';
import CoverImageForm from './Partials/CoverImageForm.vue';

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
    {
        id: "location",
        label: "Lieu LAN",
        icon: "location-dot"
    },
    {
        id: "cover",
        label: "Image de couverture",
        icon: "panorama"
    },
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
        <template #location>
            <LocationForm/>
        </template>
        <template #cover>
            <CoverImageForm/>
        </template>
    </TabsVertical>
</template>
