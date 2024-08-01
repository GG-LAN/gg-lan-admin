<script setup>
import axios from 'axios';
import { watch } from 'vue';
import { useForm } from '@inertiajs/vue3'
import BoxDrawer from '@/Components/Ui/BoxDrawer.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import InputError from '@/Components/Forms/InputError.vue';
import Checkbox from '@/Components/Forms/Checkbox.vue';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    modelId: {
    },
    drawer: {
    },
    uid: {
        type: Number
    }
});

let form = useForm({
    name: "",
    places: ""
});

watch(() => props.modelId, id => {
    if (props.drawer.isVisible()) {
        axios.get(route("games.show.api", id))
        .then(({data}) => {
            form.name   = data.data.name;
            form.places = data.data.places;
        })
    }
});

const submit = () => {
    form.put(route("games.update", props.modelId), {
        preserveScroll: true,
        onSuccess: () => {
            props.drawer.hide();
        }
    })
}

const close = () => {
    props.drawer.hide()
}
</script>

<template>
    <BoxDrawer :uid="'drawer-update-' + props.uid" :title="props.title" :drawer="props.drawer">
        <form @submit.prevent="submit">
            <div class="space-y-4">
                <div>
                    <InputLabel for="create-name" value="Nom" />
                    <TextInput
                        id="create-name"
                        type="text"
                        v-model="form.name"
                        placeholder="CS:GO"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="create-places" value="Joueurs/Équipe" />
                    <TextInput
                        id="create-places"
                        type="number"
                        v-model="form.places"
                        placeholder="1 = Jeu solo | 2+ = Jeu par équipe"
                        min="1"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.places" />
                </div>

                <div class="bottom-0 left-0 flex justify-center w-full pb-4 space-x-4 md:px-4 md:absolute">
                    <button :disabled="form.processing" type="submit" class="text-white w-full justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Mettre à jour
                    </button>
                    <button :disabled="form.processing" @click="close" type="button" :data-drawer-dismiss="'drawer-update-' + props.uid" :aria-controls="'drawer-update-' + props.uid" class="inline-flex w-full justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        <svg aria-hidden="true" class="w-5 h-5 -ml-1 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </BoxDrawer>
</template>