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

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    drawer: {
        type: Object
    },
    uid: {
        type: Number
    }
});

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

let form = useForm({
    name: "",
    description: "",
    game_id: "",
    start_date: "",
    end_date: "",
    places: "",
    cashprize: "",
    status: "",
    image: null,
    type: "",
    normal_place_price: "",
    last_week_place_price: ""
});

const submit = () => {
    form.post(route("tournaments.store"), {
        preserveScroll: true,
        forceFormData: true,
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
    <BoxDrawer :uid="'drawer-create-' + props.uid" :title="props.title" :drawer="props.drawer">
        <form @submit.prevent="submit" enctype='multipart/form-data'>
            <div class="space-y-4">
                <!-- Name -->
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

                <!-- Description -->
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

                <!-- Game -->
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

                <!-- Start Date -->
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

                <!-- End Date -->
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

                <!-- Places -->
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

                <!-- Cashprize -->
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

                <!-- Place price normal -->
                <div>
                    <InputLabel for="create-normal_place_price" value="Prix place" />
                    <TextInput
                        id="create-normal_place_price"
                        type="text"
                        v-model="form.normal_place_price"
                        placeholder="30"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.normal_place_price" />
                </div>

                <!-- Place price last week -->
                <div>
                    <InputLabel for="create-last_week_place_price" value="Prix place dernière semaine" />
                    <TextInput
                        id="create-last_week_place_price"
                        type="text"
                        v-model="form.last_week_place_price"
                        placeholder="35"
                    />
                    <InputError class="mt-2" :message="form.errors.last_week_place_price" />
                </div>

                <!-- Image -->
                <div>
                    <InputLabel for="create-image" value="Image" />
                    <input 
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="create-image" 
                        type="file" 
                        @input="form.image = $event.target.files[0]" 
                    />
                    <InputError class="mt-2" :message="form.errors.image" />
                </div>

                <div class="left-0 flex justify-center w-full pb-4 space-x-4 md:px-4 md:absolute">
                    <button :disabled="form.processing" type="submit" class="text-white w-full justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Ajouter
                    </button>
                    <button :disabled="form.processing" @click="close" type="button" :data-drawer-dismiss="'drawer-create-' + props.uid" :aria-controls="'drawer-create-' + props.uid" class="inline-flex w-full justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        <svg aria-hidden="true" class="w-5 h-5 -ml-1 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </BoxDrawer>
</template>