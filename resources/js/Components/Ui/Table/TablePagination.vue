<script setup>
import NavigationButton from "@/Components/Ui/Table/NavigationButton.vue";
import SelectPerPage from "@/Components/Ui/Table/SelectPerPage.vue";

const emit = defineEmits(["updatePerPage"]);

const props = defineProps({
    table: {
        type: Object,
        required: true,
    },
    perPage: {
        default: 5,
    },
});

const updatePerPage = (perPage) => {
    emit("updatePerPage", perPage);
};
</script>

<template>
    <div
        class="sticky bottom-0 right-0 items-center w-full sm:flex sm:justify-between"
        v-if="table.miscs.pagination && table.data.total > table.data.per_page"
    >
        <div class="flex items-center mb-4 sm:mb-0 grid grid-cols-2 space-y-2">
            <span
                class="text-sm font-normal text-gray-500 dark:text-gray-400 col-span-2"
            >
                Affiche
                <span class="font-semibold text-gray-900 dark:text-white">
                    {{ table.data.from }}
                </span>
                -
                <span class="font-semibold text-gray-900 dark:text-white">
                    {{ table.data.to }}
                </span>
                sur
                <span class="font-semibold text-gray-900 dark:text-white">
                    {{ table.data.total }}
                </span>
                résultat(s)
            </span>

            <SelectPerPage
                class="col-span-1"
                v-if="table.miscs.displayPerPage"
                :perPage="perPage"
                @updatePerPage="(perPage) => updatePerPage(perPage)"
            />
        </div>

        <div class="flex items-center space-x-2">
            <NavigationButton
                icon="angles-left"
                :link="table.data.first_page_url"
            />
            <NavigationButton
                icon="angle-left"
                :link="table.data.prev_page_url"
            />
            <NavigationButton
                icon="angle-right"
                :link="table.data.next_page_url"
            />
            <NavigationButton
                icon="angles-right"
                :link="table.data.last_page_url"
            />
        </div>
    </div>
</template>
