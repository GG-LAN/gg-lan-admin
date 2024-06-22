<script setup>
import { onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3'
import BoxDrawer from '@/Components/Ui/BoxDrawer.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import InputError from '@/Components/Forms/InputError.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';

let games = [];
let status = [];
let type = [];

onMounted(() => {
    axios.get(route("games.index.api"))
    .then(({data}) => {
        games = data
    })

    status = [
        {id: "open", name: "Ouvert"},
        {id: "closed", name: "Fermé"},
        {id: "finished", name: "Terminé"}
    ];

    type = [
        {id: "team", name: "Équipe"},
        {id: "solo", name: "Solo"}
    ]
})


const props = defineProps({
    title: {
        type: String,
        required: true
    },
    drawer: {
        type: Object
    }
});

let form = useForm({
    name: "",
    description: "",
    game_id: "",
    start_date: "",
    end_date: "",
    places: "",
    cashprize: "",
    status: "",
    // image: "",
    type: "",
});

const submit = () => {
    form.post(route("tournaments.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            props.drawer.hide();
        }
    })
}

const close = () => {
    props.drawer.hide()
}
</script>

<template>
    <BoxDrawer id="drawer-create" :title="props.title" :drawer="props.drawer">
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
                    <InputLabel for="create-description" value="Description" />
                    <TextInput
                        id="create-description"
                        type="text"
                        v-model="form.description"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <div>
                    <InputLabel for="create-game_id" value="Jeu" />
                    <SelectInput
                        id="create-game_id"
                        v-model="form.game_id"
                        placeholder="Choisir un jeu ..."
                        :data="games"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.game_id" />
                </div>

                <div>
                    <InputLabel for="create-type" value="Type" />
                    <SelectInput
                        id="create-type"
                        v-model="form.type"
                        :data="type"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.type" />
                </div>

                <div>
                    <InputLabel for="create-start_date" value="Date de début" />
                    <TextInput
                        id="create-start_date"
                        type="date"
                        v-model="form.start_date"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.start_date" />
                </div>

                <div>
                    <InputLabel for="create-end_date" value="Date de fin" />
                    <TextInput
                        id="create-end_date"
                        type="date"
                        v-model="form.end_date"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.end_date" />
                </div>

                <div>
                    <InputLabel for="create-places" value="Nombre de places" />
                    <TextInput
                        id="create-places"
                        type="number"
                        v-model="form.places"
                        min="1"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.places" />
                </div>

                <div>
                    <InputLabel for="create-cashprize" value="Cashprize" />
                    <TextInput
                        id="create-cashprize"
                        type="text"
                        v-model="form.cashprize"
                        placeholder="xxx €"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.cashprize" />
                </div>

                <div>
                    <InputLabel for="create-status" value="Statut" />
                    <SelectInput
                        id="create-status"
                        v-model="form.status"
                        :data="status"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.status" />
                </div>


                <!-- <div>
                    <InputLabel for="create-image" value="Image" />
                    <TextInput
                        id="create-image"
                        type="file"
                        v-model="form.image"
                    />
                    <InputError class="mt-2" :message="form.errors.image" />
                </div> -->

                <div class="left-0 flex justify-center w-full pb-4 space-x-4 md:px-4 md:absolute">
                    <button :disabled="form.processing" type="submit" class="text-white w-full justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Ajouter
                    </button>
                    <button :disabled="form.processing" @click="close" type="button" data-drawer-dismiss="drawer-create" aria-controls="drawer-create" class="inline-flex w-full justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        <svg aria-hidden="true" class="w-5 h-5 -ml-1 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </BoxDrawer>
</template>