<script setup>
import SuccessButton from "@/Components/Forms/SuccessButton.vue";
import SecondaryButton from "@/Components/Forms/SecondaryButton.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import Col from "@/Components/Ui/Col.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import TextareaInput from "@/Components/Forms/TextareaInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import Stepper from "@/Components/Ui/Stepper.vue";
import Modal from "@/Components/Forms/Modal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const page = usePage();

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
    last_week_place_price: "",
});

const games = ref([]);
const status = [
    { id: "open", name: "Ouvert" },
    { id: "closed", name: "Fermé" },
    { id: "finished", name: "Terminé" },
];
const type = [
    { id: "team", name: "Équipe" },
    { id: "solo", name: "Solo" },
];

const steps = [
    {
        id: "1",
        icon: "info",
        active: false,
    },
    {
        id: "2",
        icon: "gamepad",
        active: false,
    },
    {
        id: "3",
        icon: "calendar-days",
        active: false,
    },
    {
        id: "4",
        icon: "money-bill",
        active: false,
    },
];

onMounted(() => {
    axios.get(route("games.index.api")).then(({ data }) => {
        games.value = data.data;
    });
});

const submit = () => {
    form.post(route("tournaments.store"), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            closeModal();
        },
    });
};

const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};
</script>

<template>
    <SuccessButton @click="openModal"> Create </SuccessButton>

    <Modal :show="showModal" @close="closeModal" maxWidth="4xl">
        <template #header>
            {{ __("Create a tournament") }}

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400"></p>
        </template>
        <template #body>
            <Stepper :steps="steps" class="mb-4" />

            <form
                @submit.prevent="submit"
                class="grid grid-cols-2 gap-4"
                enctype="multipart/form-data"
            >
                <Col size="2" title="Tournament Info" />

                <!-- Name -->
                <Col size="1">
                    <InputLabel
                        for="create-name"
                        value="Nom"
                        :required="true"
                    />
                    <TextInput
                        id="create-name"
                        type="text"
                        v-model="form.name"
                        placeholder="CS:GO"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </Col>

                <!-- Image -->
                <Col size="1">
                    <InputLabel for="create-image" value="Image" />
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="create-image"
                        type="file"
                        @input="form.image = $event.target.files[0]"
                    />
                    <InputError class="mt-2" :message="form.errors.image" />
                </Col>

                <!-- Description -->
                <Col size="2">
                    <InputLabel
                        for="create-description"
                        value="Description"
                        :required="true"
                    />
                    <TextareaInput
                        id="create-description"
                        type="text"
                        :placeholder="
                            __('Short description of the tournament') + '...'
                        "
                        v-model="form.description"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.description"
                    />
                </Col>

                <Col size="2" title="Tournament Game" />

                <!-- Game -->
                <Col size="1">
                    <InputLabel
                        for="create-game_id"
                        value="Jeu"
                        :required="true"
                    />
                    <SelectInput
                        id="create-game_id"
                        v-model="form.game_id"
                        placeholder="Choisir un jeu ..."
                        :data="games"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.game_id" />
                </Col>

                <!-- Places -->
                <Col size="1">
                    <InputLabel
                        for="create-places"
                        value="Nombre de places"
                        :required="true"
                    />
                    <TextInput
                        id="create-places"
                        type="number"
                        v-model="form.places"
                        min="1"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.places" />
                </Col>

                <Col size="2" title="Tournament Dates" />

                <!-- Start Date -->
                <Col size="1">
                    <InputLabel
                        for="create-start_date"
                        value="Date de début"
                        :required="true"
                    />
                    <TextInput
                        id="create-start_date"
                        type="date"
                        v-model="form.start_date"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.start_date"
                    />
                </Col>

                <!-- End Date -->
                <Col size="1">
                    <InputLabel
                        for="create-end_date"
                        value="Date de fin"
                        :required="true"
                    />
                    <TextInput
                        id="create-end_date"
                        type="date"
                        v-model="form.end_date"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.end_date" />
                </Col>

                <Col size="2" title="Tournament Prices" />

                <!-- Cashprize -->
                <Col size="2">
                    <InputLabel for="create-cashprize" value="Cashprize" />
                    <TextInput
                        id="create-cashprize"
                        type="text"
                        v-model="form.cashprize"
                        placeholder="xxx €"
                    />
                    <InputError class="mt-2" :message="form.errors.cashprize" />
                </Col>

                <!-- Place price normal -->
                <Col size="1">
                    <InputLabel
                        for="create-normal_place_price"
                        value="Prix place"
                        :required="true"
                    />
                    <TextInput
                        id="create-normal_place_price"
                        type="text"
                        v-model="form.normal_place_price"
                        placeholder="30"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.normal_place_price"
                    />
                </Col>

                <!-- Place price last week -->
                <Col size="1">
                    <InputLabel
                        for="create-last_week_place_price"
                        value="Prix place dernière semaine"
                    />
                    <TextInput
                        id="create-last_week_place_price"
                        type="text"
                        v-model="form.last_week_place_price"
                        placeholder="35"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.last_week_place_price"
                    />
                </Col>

                <div id="info"></div>
                <div id="game"></div>
                <div id="dates"></div>
                <div class="flex justify-end">
                    <SecondaryButton @click="closeModal" class="mr-4">
                        {{ __("Cancel") }}
                    </SecondaryButton>
                    <SubmitButton :form="form" color="success">
                        {{ __("Create") }}
                    </SubmitButton>
                </div>
            </form>
        </template>
    </Modal>
</template>
