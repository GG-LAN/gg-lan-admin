<script setup>
import debounce from 'lodash/debounce';
import { initFlowbite, Drawer } from 'flowbite';
import { onMounted, ref, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import SvgIcon from "@/Components/Ui/SvgIcon.vue";
import DangerButton from "@/Components/Forms/DangerButton.vue"
import CreateButton from "@/Components/Forms/CreateButton.vue"
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue"
import TablePagination from '@/Components/Ui/TablePagination.vue';
import TableLabelBool from '@/Components/Ui/TableLabelBool.vue';
import TableLabelStatus from '@/Components/Ui/TableLabelStatus.vue';

let drawerCreate;
let drawerUpdate;
let drawerDelete;

onMounted(() => {
    initFlowbite();

    const drawerCreateElement = document.querySelector("#drawer-create");
    if (drawerCreateElement) {
        drawerCreate = new Drawer(drawerCreateElement, {
            placement: "right",
            backdrop: true,
        });
    }
    
    const drawerUpdateElement = document.querySelector("#drawer-update");
    if (drawerUpdateElement) {
        drawerUpdate = new Drawer(drawerUpdateElement, {
            placement: "right",
            backdrop: true,
        });
    }
    
    const drawerDeleteElement = document.querySelector("#drawer-delete");
    if (drawerDeleteElement) {
        drawerDelete = new Drawer(drawerDeleteElement, {
            placement: "right",
            backdrop: true,
        });
    }
})

const page  = usePage();
let loading = ref(false);
let modelId = ref();
let search  = ref();

if (page.props['filters']) {
    search = ref(page.props.filters.search);
}


const props = defineProps({
    rows: {
        type: Object,
        required: true
    },
    rowsInfo: {
        type: Object,
        required: true
    },
    route: {
        type: String,
        required: true
    }
});

watch(search, debounce(value => {
    router.get(props.route, {
        search: value
    },{ 
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onStart: () => {
            loading.value = true;
        },
        onFinish: () => {
            loading.value = false;
        }
    });
}, 300));

const checkAll = () => {
    const checkboxAll = document.querySelector("#checkbox-all");

    const checkboxes = document.querySelectorAll(".checkAll");

    checkboxes.forEach(checkbox => {
        checkbox.checked = checkboxAll.checked;
    })
}

const redirectTo = (id) => {
    router.get(route(props.rowsInfo.actions.show.route, id))
}

const refresh = () => {
    router.get(page.url, {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onStart: () => {
            loading.value = true;
        },
        onFinish: () => {
            loading.value = false;
        }
    });
}

const openDrawer = (drawer, id = null) => {
    modelId.value = id;

    switch (drawer) {
        case "create":
            drawerCreate.show();
        break;
        case "update":
            drawerUpdate.show();
            
        break;
        case "delete":
            drawerDelete.show();
        break;
    
        default:
            break;
    }

}

</script>

<template>
    <!-- Header -->
    <div class="pb-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-800">
                <div class="flex items-center mb-4 sm:mb-0">
                    <!-- Search field -->
                    <form class="sm:pr-3" v-if="rowsInfo.actions['search']">
                        <label for="search" class="sr-only">Rechercher</label>
                        <div class="relative mt-1 lg:w-96">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>

                            <input
                                v-model="search" 
                                type="text"
                                name="search"
                                id="search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                                placeholder="Rechercher..."
                            >
                        </div>
                    </form>

                    <!-- Multiple delete button -->
                    <!-- <div class="flex items-center w-full sm:justify-end">
                        <div class="flex pl-2 space-x-1">
                            <button type="button" id="deleteChecked" class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <SvgIcon icon="trash-can" class="w-6 h-6"/>
                            </button>
                        </div>
                    </div> -->
                </div>
                <div class="flex items-center mb-4 sm:mb-0 space-x-3">
                    <!-- Create drawer -->
                    <CreateButton id="create" class="text-sm" icon="plus" v-if="rowsInfo.actions['create']" @click="openDrawer('create')">
                        <span class="ml-2">Ajouter</span>
                    </CreateButton>
                        
                    <PrimaryButton id="reload" class="text-sm" icon="sync" :loading="loading" @click="refresh">
                        <span class="ml-2">Rafraîchir</span>
                    </PrimaryButton>

                    <slot name="buttons"/>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                        <!-- Headers -->
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <!-- Checkbox all -->
                                <th scope="col" class="p-4">
                                    <div class="flex items-center" @click="checkAll">
                                        <input id="checkbox-all" aria-describedby="checkbox-all" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-all" class="sr-only">checkbox</label>
                                    </div>
                                </th>

                                <!-- Headers -->
                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400" v-for="{title} in rowsInfo.rows">
                                    <div class="flex items-center">
                                        {{ title }}
                                        <SvgIcon icon="sort" class="w-3 h-3 ms-1.5"/>
                                    </div>
                                </th>

                                <!-- Actions buttons -->
                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400" v-if="rowsInfo.actions.update || rowsInfo.actions.delete">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <!-- Rows -->
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-center text-gray-800 dark:text-gray-400" v-if="!rows.data">
                                <td :colspan="Object.keys(rowsInfo.rows).length + 2" class="py-2" v-if="rowsInfo.actions.update || rowsInfo.actions.delete">Pas de résultats...</td>
                                <td :colspan="Object.keys(rowsInfo.rows).length + 1" class="py-2" v-else>Pas de résultats...</td>
                            </tr>

                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" v-for="row in rows.data" :key="row.id" :class="rowsInfo.actions.show ? 'cursor-pointer':''" v-else>
                                <!-- Checkbox -->
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input 
                                            :id="'checkbox-' + row.id"
                                            type="checkbox"
                                            class="checkAll w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                        >
                                        <label :for="'checkbox-' + row.id" class="sr-only">checkbox</label>
                                    </div>
                                </td>

                                <!-- Data -->
                                <td class="p-4 text-base font-medium overflow-hidden truncate xl:max-w-xs text-gray-900 whitespace-nowrap dark:text-white" @click="rowsInfo.actions.show ? redirectTo(row.id): ''" v-for="(rowInfo, key) in rowsInfo.rows">
                                    <span v-if="rowInfo.type == 'text'">{{ row[key] }}</span>

                                    <div v-if="rowInfo.type == 'bool'">
                                        <TableLabelBool :rowInfo="rowInfo" :row="row" :rowKey="key" />
                                    </div>

                                    <div class="flex items-center" v-if="rowInfo.type == 'status'">
                                        <TableLabelStatus :rowInfo="rowInfo" :row="row" :rowKey="key" />
                                    </div>
                                </td>

                                <!-- Buttons -->
                                <td class="p-4 space-x-2 whitespace-nowrap" v-if="rowsInfo.actions.update || rowsInfo.actions.delete">
                                    <PrimaryButton :id="'update-'+row.id" class="text-sm" icon="pen-to-square" v-if="rowsInfo.actions.update" @click="openDrawer('update', row.id)">
                                    </PrimaryButton>

                                    <DangerButton :id="'delete-'+row.id" class="text-sm" icon="trash-can" v-if="rowsInfo.actions.delete" @click="openDrawer('delete', row.id)">
                                    </DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <TablePagination :rows="rows"/>

    <!-- CRUD Drawers -->
    <slot name="drawerCreate" :drawer="drawerCreate" v-if="rows.data"/>
    <slot name="drawerUpdate" :drawer="drawerUpdate" :modelId="modelId" v-if="rows.data"/>
    <slot name="drawerDelete" :drawer="drawerDelete" :modelId="modelId" v-if="rows.data"/>
</template>
