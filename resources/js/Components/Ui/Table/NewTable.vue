<script setup>
import debounce from 'lodash/debounce';
import { Drawer } from 'flowbite';
import { onMounted, ref, watch, getCurrentInstance, nextTick } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

import SvgIcon from "@/Components/Ui/SvgIcon.vue";
import DangerButton from "@/Components/Forms/DangerButton.vue"
import SuccessButton from "@/Components/Forms/SuccessButton.vue"
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue"

import TablePagination from '@/Components/Ui/Table/TablePagination.vue';
import TableBool from '@/Components/Ui/Table/TableBool.vue';
import TableBadge from '@/Components/Ui/Table/TableBadge.vue';
import TableEnum from '@/Components/Ui/Table/TableEnum.vue';
import TableCheckbox from '@/Components/Ui/Table/TableCheckbox.vue';
import TableCustomButtonAction from '@/Components/Ui/Table/TableCustomButtonAction.vue';
import FilterButton from '@/Components/Ui/Table/FilterButton.vue';

import {
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'

const props = defineProps({
    table: {
        type: Object,
        required: true
    }
});

let drawerCreate;
let drawerUpdate;
let drawerDelete;

const page  = usePage();
let loading = ref(false);
let modelId = ref();
let search  = ref();
let sort    = ref();
let uid = ref();

onMounted(() => {
    // Unique id for each instance of this component
    uid.value = getCurrentInstance().uid;
 })


if (page.props.table.search) {
    search = ref(page.props.table.search);
}

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
    router.get(route(route().current()), {
        search: value,
        sort: sort.value
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

watch(sort, value => {
    router.get(route(route().current()), {
        search: search.value,
        sort: value
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
})

const checkAll = (event) => {
    const checkboxAll = event.target;
    const checkboxes = document.querySelectorAll(".checkAll");

    checkboxes.forEach(checkbox => {
        checkbox.checked = checkboxAll.checked;
    })
}

const redirectTo = (id) => {
    router.get(route(props.table.actions.show, id))
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

const sortColumn = (column) => {
    let calculatedSort = "";
    let params = route().queryParams;

    if (params.sort && params.sort.includes(column + ",asc")) {
        calculatedSort = column + ",desc" 
    }
    else if (params.sort && params.sort.includes(column + ",desc")) {
        calculatedSort = "";
    }
    else {
        calculatedSort = column + ",asc" 
    }
    
    sort.value = calculatedSort
}
</script>

<template>
    <!-- Header -->
    <div class="pb-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-800">
                <div class="flex items-center mb-4 sm:mb-0">
                    <!-- Search field -->
                    <form class="sm:pr-3" v-if="table.actions.search">
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
                                :id="'search-'+uid"
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
                    <SuccessButton id="create" class="text-sm" icon="plus" v-if="table.actions.create" @click="openDrawer('create')">
                        <span class="ml-2">Ajouter</span>
                    </SuccessButton>
                        
                    
                    <FilterButton :table="table" v-if="Object.entries(table.filters).length > 0"/>
                    
                    <PrimaryButton id="reload" class="text-sm" icon="sync" :loading="loading" @click="refresh">
                        <!-- <span class="ml-2">Rafraîchir</span> -->
                    </PrimaryButton>
                    <slot name="buttons"/>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <fwb-table hoverable striped class="overflow-x-auto">
        <!-- Head -->
        <fwb-table-head>
            <!-- Checkbox -->
            <fwb-table-head-cell class="px-4 py-4 font-medium" v-if="table.actions.checkbox">
                <div class="flex items-center" @click="checkAll">
                    <input id="checkbox-all" aria-describedby="checkbox-all" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-all" class="sr-only">checkbox</label>
                </div>
            </fwb-table-head-cell>

            <!-- Headers -->
            <fwb-table-head-cell v-for="(column, key) in table.columns" class="px-4 py-4 font-medium">
                <!-- Sortable Column -->
                <div class="flex items-center cursor-pointer select-none" v-if="column.sortable" @click="sortColumn(key)">
                    {{ column.label }}
                    <div v-if="table.sort && table.sort.column == key">
                        <SvgIcon icon="sort-up" class="w-3 h-3 ms-1.5 cursor-pointer text-white" v-if="table.sort.sort == 'asc'"/>
                        <SvgIcon icon="sort-down" class="w-3 h-3 ms-1.5 cursor-pointer text-white" v-else/>
                    </div>
                    <div v-else>
                        <SvgIcon icon="sort" class="w-3 h-3 ms-1.5 cursor-pointer"/>
                    </div>
                </div>

                <!-- Simple Column -->
                <div class="flex items-center select-none" v-else>
                    {{ column.label }}
                </div>
            </fwb-table-head-cell>

            <!-- Actions -->
            <fwb-table-head-cell v-if="table.actions.update || table.actions.delete || table.actions.customActions" class="px-4 py-4 font-medium">
                Actions
            </fwb-table-head-cell>
        </fwb-table-head>

        <!-- Body -->
        <fwb-table-body class="divide-y divide-gray-200 dark:divide-gray-700">
            <!-- No data row -->
            <fwb-table-row v-if="table.data.data.length === 0" class="text-center">
                <fwb-table-cell v-if="table.actions.update || table.actions.delete" :colspan="table.miscs.columns_count + 2" class="py-2">
                    Pas de résultats...
                </fwb-table-cell>
                <fwb-table-cell v-else :colspan="table.miscs.columns_count + 1" class="py-2">
                    Pas de résultats...
                </fwb-table-cell>
            </fwb-table-row>

            <fwb-table-row v-for="row in table.data.data" :key="row.id" :class="table.actions.show ? 'cursor-pointer':''" v-else>
                <!-- Checkbox -->
                <fwb-table-cell v-if="table.actions.checkbox">
                    <!-- <TableCheckbox :checkboxId="table.actions.checkbox.id" :row="row" /> -->
                </fwb-table-cell>

                <!-- Data -->
                <td v-for="(column, key) in table.columns" @click="table.actions.show ? redirectTo(row.id): ''" class="p-4 text-base font-medium xl:max-w-xs text-gray-900  dark:text-white [&:not(:hover)]:truncate">
                    <span v-if="column.type == 'bool'">
                        <TableBool :column="column" :value="row[key]" />
                    </span>
                    
                    <span v-if="column.type == 'badge'">
                        <TableBadge :column="column" :value="row[key]" />
                    </span>

                    <span v-if="column.type == 'enum'">
                        <TableEnum :column="column" :value="row[key]" />
                    </span>

                    <span v-if="column.type == 'text' || column.type == 'date' || column.type == 'compact' ">
                        {{ row[key] }}
                    </span>
                </td>

                <!-- Action Buttons -->
                <fwb-table-cell v-if="table.actions.update || table.actions.delete || table.actions.customActions" class="whitespace-nowrap space-x-2">
                    <PrimaryButton :id="'update-'+row.id" class="text-sm" icon="pen-to-square" v-if="table.actions.update" @click="openDrawer('update', row.id)">
                    </PrimaryButton>

                    <DangerButton :id="'delete-'+row.id" class="text-sm" icon="trash-can" v-if="table.actions.delete" @click="openDrawer('delete', row.id)">
                    </DangerButton>

                    <TableCustomButtonAction v-if="table.actions.customActions" v-for="customAction in table.actions.customActions" :key="row.id" :customAction="customAction" :row="row" />
                </fwb-table-cell>
            </fwb-table-row>
        </fwb-table-body>
    </fwb-table>

    <!-- Pagination -->
    <TablePagination :table="table"/>

    <!-- CRUD Drawers -->
    <slot name="drawerCreate" :drawer="drawerCreate" :uid="uid" v-if="table.data.data"/>
    <slot name="drawerUpdate" :drawer="drawerUpdate" :modelId="modelId" :uid="uid" v-if="table.data.data"/>
    <slot name="drawerDelete" :drawer="drawerDelete" :modelId="modelId" :uid="uid" v-if="table.data.data"/>
</template>
