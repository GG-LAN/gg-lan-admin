<script setup>
import { onMounted, ref, nextTick } from 'vue';

const emit = defineEmits([
    'updatePerPage'
])

const props = defineProps({
    perPage: {
        required: true
    }
});

const perPage = ref();

onMounted(() => {
    nextTick(() => {
        perPage.value = props.perPage;
    });  
})


const changePerPage = (event) => {
    perPage.value = event.target.value;
        
    emit('updatePerPage', perPage.value);
}
</script>

<template> 
    <form class="max-w-sm mx-auto flex flex-inline justify-between items-center space-x-2">
        <label for="perPage" class="text-sm font-normal text-gray-500 dark:text-gray-400">Afficher</label>
        <select id="perPage" v-model="perPage" @change="changePerPage" class="flex bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-1.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
        </select>

        <label for="perPage" class="text-sm font-normal text-gray-500 dark:text-gray-400">lignes</label>
    </form>
</template>