<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue"
import SvgIcon from '@/Components/Ui/SvgIcon.vue';

const props = defineProps({
    rows: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="sticky bottom-0 right-0 items-center w-full py-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center mb-4 sm:mb-0">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                Affiche
                <span class="font-semibold text-gray-900 dark:text-white">{{ rows.from }}</span>
                -
                <span class="font-semibold text-gray-900 dark:text-white">{{ rows.to }}</span>
                sur
                <span class="font-semibold text-gray-900 dark:text-white">{{ rows.total }}</span>
                résultat(s)
            </span>
        </div>

        <!-- Buttons Previous & Next -->
        <div class="flex items-center space-x-3">
            <!-- <Link icon="arrow-left" v-if="rows.prev_page_url" :href="rows.prev_page_url" preserve-scroll class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <SvgIcon class="w-4 h-4 mr-2" icon="arrow-left" />
                Précédent
            </Link>
            <PrimaryButton icon="arrow-left" class="text-sm font-medium cursor-not-allowed" disabled v-else>
                Précédent
            </PrimaryButton>

            <Link v-if="rows.next_page_url" :href="rows.next_page_url" preserve-scroll class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                Suivant
                <SvgIcon class="w-4 h-4 ml-2" icon="arrow-right" />
            </Link>
            <PrimaryButton class="text-sm font-medium cursor-not-allowed" disabled v-else>
                Suivant
                <SvgIcon class="w-4 h-4 ml-2" icon="arrow-right" />
            </PrimaryButton> -->
            
            <nav aria-label="Page navigation example">
                <ul class="flex items-center -space-x-px h-10 text-base">
                    <li v-if="rows.prev_page_url">
                        <Link :href="rows.prev_page_url" preserve-scroll class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <SvgIcon class="w-4 h-4" icon="chevron-left" />
                            <span class="sr-only">Previous</span>
                        </Link>
                    </li>
                    <li v-else class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <SvgIcon class="w-4 h-4" icon="chevron-left" />
                        <span class="sr-only">Previous</span>
                    </li>

                    <li v-for="(link, key) in rows.links">
                        <Link
                            v-if="key != 0 && key != (rows.links.length - 1)"
                            :href="link.url"
                            preserve-scroll
                            :class="
                                link.active ? 'z-10 text-blue-600 border-blue-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                                : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'
                            "
                            class="flex items-center justify-center px-4 h-10 leading-tight border"
                        >
                            {{ link.label }}
                        </Link>
                    </li>

                    <li v-if="rows.next_page_url">
                        <Link class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" :href="rows.next_page_url" preserve-scroll>
                            <SvgIcon class="w-4 h-4" icon="chevron-right" />
                            <span class="sr-only">Next</span>
                        </Link>
                    </li>
                    <li v-else class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <SvgIcon class="w-4 h-4" icon="chevron-right" />
                            <span class="sr-only">Next</span>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
</template>