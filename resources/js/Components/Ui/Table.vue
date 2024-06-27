<script setup>
import debounce from 'lodash/debounce';
import { Drawer } from 'flowbite';
import { onMounted, ref, watch, getCurrentInstance, nextTick } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import SvgIcon from "@/Components/Ui/SvgIcon.vue";
import DangerButton from "@/Components/Forms/DangerButton.vue"
import SuccessButton from "@/Components/Forms/SuccessButton.vue"
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue"
import TablePagination from '@/Components/Ui/TablePagination.vue';
import TableLabelBool from '@/Components/Ui/TableLabelBool.vue';
import TableLabelStatus from '@/Components/Ui/TableLabelStatus.vue';
import TableCheckbox from '@/Components/Ui/TableCheckbox.vue';
import {
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'


let drawerCreate;
let drawerUpdate;
let drawerDelete;

onMounted(() => {
    // Unique id for each instance of this component
    uid.value = getCurrentInstance().uid;
})

const page  = usePage();
let loading = ref(false);
let modelId = ref();
let search  = ref();
let uid = ref();

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

watch(
    () => uid.value,
    () => {
        nextTick(() => {
            const drawerCreateElement = document.querySelector("#drawer-create-" + uid.value);
            if (drawerCreateElement) {
                drawerCreate = new Drawer(drawerCreateElement, {
                    placement: "right",
                    backdrop: true,
                });
            }

            const drawerUpdateElement = document.querySelector("#drawer-update-" + uid.value);
            if (drawerUpdateElement) {
                drawerUpdate = new Drawer(drawerUpdateElement, {
                    placement: "right",
                    backdrop: true,
                });
            }
            
            const drawerDeleteElement = document.querySelector("#drawer-delete-" + uid.value);
            if (drawerDeleteElement) {
                drawerDelete = new Drawer(drawerDeleteElement, {
                    placement: "right",
                    backdrop: true,
                });
            }
        });
    }
);

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

const checkAll = (event) => {
    const checkboxAll = event.target;
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
                    <SuccessButton id="create" class="text-sm" icon="plus" v-if="rowsInfo.actions['create']" @click="openDrawer('create')">
                        <span class="ml-2">Ajouter</span>
                    </SuccessButton>
                        
                    <PrimaryButton id="reload" class="text-sm" icon="sync" :loading="loading" @click="refresh">
                        <span class="ml-2">Rafraîchir</span>
                    </PrimaryButton>

                    <slot name="buttons"/>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <fwb-table hoverable striped>
        <!-- Head -->
        <fwb-table-head>
            <!-- Checkbox -->
            <fwb-table-head-cell class="px-4 py-4 font-medium" v-if="rowsInfo.actions.checkbox">
                <div class="flex items-center" @click="checkAll">
                    <input id="checkbox-all" aria-describedby="checkbox-all" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-all" class="sr-only">checkbox</label>
                </div>
            </fwb-table-head-cell>

            <!-- Headers -->
            <fwb-table-head-cell v-for="row in rowsInfo.rows" class="px-4 py-4 font-medium">
                <div class="flex items-center">
                    {{ row.title }}
                    <!-- <SvgIcon icon="sort" class="w-3 h-3 ms-1.5"/> -->
                </div>
            </fwb-table-head-cell>

            <!-- Actions -->
            <fwb-table-head-cell v-if="rowsInfo.actions.update || rowsInfo.actions.delete" class="px-4 py-4 font-medium">
                Actions
            </fwb-table-head-cell>
        </fwb-table-head>

        <!-- Body -->
        <fwb-table-body class="divide-y divide-gray-200 dark:divide-gray-700">
            <!-- No data row -->
            <fwb-table-row v-if="false" class="text-center">
                <fwb-table-cell v-if="rowsInfo.actions.update || rowsInfo.actions.delete" :colspan="Object.keys(rowsInfo.rows).length + 2" class="py-2">
                    Pas de résultats...
                </fwb-table-cell>
                <fwb-table-cell v-else :colspan="Object.keys(rowsInfo.rows).length + 1" class="py-2">
                    Pas de résultats...
                </fwb-table-cell>
            </fwb-table-row>

            <fwb-table-row v-for="row in rows.data" :key="row.id" :class="rowsInfo.actions.show ? 'cursor-pointer':''" v-else>
                <!-- Checkbox -->
                <fwb-table-cell v-if="rowsInfo.actions.checkbox">
                    <TableCheckbox :checkboxId="rowsInfo.actions.checkbox.id" :row="row" />
                </fwb-table-cell>

                <!-- Data -->
                <fwb-table-cell @click="rowsInfo.actions.show ? redirectTo(row.id): ''" v-for="(rowInfo, key) in rowsInfo.rows" class="truncate text-base font-medium p-4 text-gray-900 dark:text-white">
                    <div v-if="rowInfo.type == 'bool'">
                        <TableLabelBool :rowInfo="rowInfo" :row="row" :rowKey="key" />
                    </div>
                    
                    <div class="flex items-center" v-if="rowInfo.type == 'status'">
                        <TableLabelStatus :rowInfo="rowInfo" :row="row" :rowKey="key" />
                    </div>

                    <span v-if="rowInfo.type == 'text'">{{ row[key] }}</span>
                </fwb-table-cell>

                <!-- Action Buttons -->
                <fwb-table-cell v-if="rowsInfo.actions.update || rowsInfo.actions.delete" class="whitespace-nowrap space-x-2">
                    <PrimaryButton :id="'update-'+row.id" class="text-sm" icon="pen-to-square" v-if="rowsInfo.actions.update" @click="openDrawer('update', row.id)">
                    </PrimaryButton>

                    <DangerButton :id="'delete-'+row.id" class="text-sm" icon="trash-can" v-if="rowsInfo.actions.delete" @click="openDrawer('delete', row.id)">
                    </DangerButton>
                </fwb-table-cell>
            </fwb-table-row>
        </fwb-table-body>
    </fwb-table>

    <!-- Pagination -->
    <TablePagination :rows="rows"/>

    <!-- CRUD Drawers -->
    <slot name="drawerCreate" :drawer="drawerCreate" :uid="uid" v-if="rows.data"/>
    <slot name="drawerUpdate" :drawer="drawerUpdate" :modelId="modelId" :uid="uid" v-if="rows.data"/>
    <slot name="drawerDelete" :drawer="drawerDelete" :modelId="modelId" :uid="uid" v-if="rows.data"/>
</template>
