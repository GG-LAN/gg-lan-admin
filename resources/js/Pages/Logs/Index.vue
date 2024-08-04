<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/Components/Ui/PageTitle.vue';
import TabsVertical from '@/Components/Ui/TabsVertical.vue';

defineOptions({layout: AuthenticatedLayout});

</script>

<template>
    <Head title="Logs" />
    <PageTitle title="Logs" :breadcrumbs="$page.props.breadcrumbs"/>
    
    <TabsVertical tabId="logsTabs" :tabs="$page.props.logs">
        <template #[log.id] v-for="log in $page.props.logs">
            <div class="">
                <div class="relative bg-gray-50 rounded-lg dark:bg-gray-700 p-4 h-96">
                    <div class="overflow-x-auto max-h-full">
                        <code id="code-block" class="text-sm text-gray-500 dark:text-gray-400 whitespace-pre"> {{ log.content }}</code>
                    </div>
                </div>
            </div>
        </template>
    </TabsVertical>
</template>
