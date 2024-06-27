<script setup>
import BoxDrawer from '@/Components/Ui/BoxDrawer.vue';
import DangerButton from '@/Components/Forms/DangerButton.vue';
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    modelId: {
        type: Number
    },
    drawer: {
        type: Object
    },
    uid: {
        type: Number
    }
});

let form = useForm({});

const submit = () => {
    form.delete(route('tournaments.destroy', props.modelId), {
        preserveScroll: true,
        onSuccess: () => {
            props.drawer.hide()
        }
    })
}

const close = () => {
    props.drawer.hide()
}
</script>

<template>
    <BoxDrawer :uid="'drawer-delete-' + props.uid" :title="props.title" :drawer="props.drawer">
        <svg class="w-10 h-10 mt-8 mb-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h3 class="mb-6 text-lg text-gray-500 dark:text-gray-400">Êtes-vous sûr(e) de vouloir supprimer ce tournois ?</h3>
        <form @submit.prevent="submit">
            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-900">
                Oui, je suis sûr(e)
            </button>
            <button type="button" @click="close" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :data-drawer-hide="'drawer-delete-' + props.uid">
                Non, annuler
            </button>
        </form>
    </BoxDrawer>
</template>