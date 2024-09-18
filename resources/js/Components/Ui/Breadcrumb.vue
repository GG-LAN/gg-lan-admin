<script setup>
import { nextTick, onMounted, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import SvgIcon from "@/Components/Ui/SvgIcon.vue";
import SecondaryButton from "@/Components/Forms/SecondaryButton.vue";
import { Dropdown } from "flowbite";

const props = defineProps({
    breadcrumbs: {
        type: Array,
    },
});

const dropdown = ref();

onMounted(() => {
    let dropdownButton = document.getElementById("breadcrumb-dropdown-button");

    if (dropdownButton) {
        dropdown.value = new Dropdown(
            document.getElementById("breadcrumb-dropdown"),
            dropdownButton,
            {
                placement: "bottom",
            }
        );
    }
});
</script>

<template>
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol
            class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2"
        >
            <li class="inline-flex items-center py-2">
                <Link
                    :href="route('dashboard')"
                    class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500"
                >
                    <SvgIcon icon="house" class="w-5 h-5 mr-2 text-gray-400" />
                    Dashboard
                </Link>
            </li>

            <li v-for="item in breadcrumbs">
                <!-- Normal Crumb -->
                <div class="flex items-center space-x-2" v-if="!item.choices">
                    <SvgIcon icon="angle-right" class="w-4 h-4 text-gray-400" />

                    <Link
                        :href="item.route"
                        class="text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500"
                        v-if="!item.active"
                    >
                        {{ item.label }}
                    </Link>

                    <span
                        class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        v-else
                    >
                        {{ item.label }}
                    </span>
                </div>

                <!-- Dropdown Crumb -->
                <div class="flex items-center space-x-2" v-else>
                    <SvgIcon icon="angle-right" class="w-4 h-4 text-gray-400" />

                    <SecondaryButton
                        class="text-sm"
                        id="breadcrumb-dropdown-button"
                    >
                        <span
                            v-if="item.status == 'closed'"
                            class="h-2.5 w-2.5 rounded-full bg-red-400"
                        >
                        </span>

                        <span
                            v-if="item.status == 'finished'"
                            class="h-2.5 w-2.5 rounded-full bg-yellow-400"
                        >
                        </span>

                        <span
                            v-if="item.status == 'open'"
                            class="h-2.5 w-2.5 rounded-full bg-green-400"
                        >
                        </span>

                        <span>{{ item.label }}</span>

                        <SvgIcon
                            icon="angle-down"
                            class="w-3.5 h-3.5 ms-2.5 text-gray-400"
                        />
                    </SecondaryButton>

                    <div
                        id="breadcrumb-dropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md w-auto dark:bg-gray-700"
                    >
                        <ul
                            class="py-2 text-sm text-gray-700 dark:text-gray-200"
                        >
                            <li v-for="choice in item.choices">
                                <Link
                                    :href="choice.route"
                                    v-if="choice"
                                    class="block px-4 py-2 flex items-center space-x-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                >
                                    <span
                                        v-if="choice.status == 'closed'"
                                        class="h-2.5 w-2.5 rounded-full bg-red-400"
                                    >
                                    </span>

                                    <span
                                        v-if="choice.status == 'finished'"
                                        class="h-2.5 w-2.5 rounded-full bg-yellow-400"
                                    >
                                    </span>

                                    <span
                                        v-if="choice.status == 'open'"
                                        class="h-2.5 w-2.5 rounded-full bg-green-400"
                                    >
                                    </span>

                                    <span>{{ choice.label }}</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ol>
    </nav>
</template>
