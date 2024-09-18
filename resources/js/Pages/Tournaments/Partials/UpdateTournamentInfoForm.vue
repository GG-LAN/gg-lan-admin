<script setup>
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import TextareaInput from "@/Components/Forms/TextareaInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Col from "@/Components/Ui/Col.vue";

const page = usePage();

const form = useForm({
    name: page.props.tournament.name,
    description: page.props.tournament.description,
    game_id: page.props.tournament.game_id,
    start_date: page.props.tournament.start_date,
    end_date: page.props.tournament.end_date,
    places: page.props.tournament.places,
    cashprize: page.props.tournament.cashprize,
});

const updateTournament = () => {
    form.put(route("tournaments.update", page.props.tournament.id), {
        preserveScroll: true,
        onSuccess: () => {},
    });
};
</script>

<template>
    <form
        @submit.prevent="updateTournament"
        class="mt-6 grid grid-cols-2 gap-4"
    >
        <!-- Name -->
        <Col size="1">
            <InputLabel for="name" value="Nom" :required="true" />
            <TextInput
                id="name"
                class="mt-1 block w-full"
                ref="name"
                type="text"
                v-model="form.name"
                autocomplete="name"
                required
            />
            <InputError :message="form.errors.name" class="mt-2" />
        </Col>

        <!-- Cashprize -->
        <Col size="1">
            <InputLabel for="update-cashprize" value="Cashprize" />
            <TextInput
                id="update-cashprize"
                class="mt-1 block w-full"
                type="text"
                v-model="form.cashprize"
                placeholder="xxx €"
            />
            <InputError class="mt-2" :message="form.errors.cashprize" />
        </Col>

        <!-- Description -->
        <Col size="2">
            <InputLabel
                for="update-description"
                value="Description"
                :required="true"
            />
            <TextareaInput
                id="update-description"
                class="mt-1 block w-full"
                type="text"
                :placeholder="__('Short description of the tournament') + '...'"
                v-model="form.description"
                required
            />
            <InputError class="mt-2" :message="form.errors.description" />
        </Col>

        <Col size="2">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __("Tournament Game") }}
            </h2>
        </Col>

        <!-- Game -->
        <Col size="1">
            <InputLabel for="update_game-game_id" value="Jeu" />
            <SelectInput
                id="update_game-game_id"
                class="mt-1 block w-full"
                v-model="form.game_id"
                placeholder="Choisir un jeu ..."
                :data="$page.props.games"
                required
            />
            <InputError class="mt-2" :message="form.errors.game_id" />
        </Col>

        <!-- Places -->
        <Col size="1">
            <InputLabel
                for="update_game-places"
                value="Nombre de places du tournois"
                :required="true"
            />
            <TextInput
                id="update_game-places"
                class="mt-1 block w-full"
                type="number"
                v-model="form.places"
                min="1"
                required
            />
            <InputError class="mt-2" :message="form.errors.places" />
        </Col>

        <Col size="2" class="flex items-center justify-end gap-4">
            <SubmitButton :form="form" color="success">
                {{ __("Update") }}
            </SubmitButton>
        </Col>
    </form>
</template>
