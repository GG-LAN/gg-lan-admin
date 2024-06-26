<script setup>
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import SubmitButton from '@/Components/Forms/SubmitButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';
import TextareaInput from '@/Components/Forms/TextareaInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    tournament: {
        type: Object,
        required: true
    },
    games: {
        type: Array,
        required: true
    }
});

const form = useForm({
    name:        props.tournament.name,
    description: props.tournament.description,
    game_id:     props.tournament.game_id,
    start_date:  props.tournament.start_date,
    end_date:    props.tournament.end_date,
    places:      props.tournament.places,
    cashprize:   props.tournament.cashprize,
});

const updateTournament = () => {
    form.put(route("tournaments.update", props.tournament.id), {
        preserveScroll: true,
        onSuccess: () => {
        }
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Informations du tournois</h2>
        </header>

        <form @submit.prevent="updateTournament" class="mt-6 grid grid-cols-2 gap-4">
            <!-- Name -->
            <div class="col-span-1">
                <InputLabel for="name" value="Nom" />

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
            </div>

            <!-- Cashprize -->
            <div class="col-span-1">
                <InputLabel for="update-cashprize" value="Cashprize" />
                <TextInput
                    id="update-cashprize"
                    class="mt-1 block w-full"
                    type="text"
                    v-model="form.cashprize"
                    placeholder="xxx €"
                    required
                />
                <InputError class="mt-2" :message="form.errors.cashprize" />
            </div>

            <!-- Description -->
            <div class="col-span-2">
                <InputLabel for="update-description" value="Description" />
                <TextareaInput
                    id="update-description"
                    class="mt-1 block w-full"
                    type="text"
                    placeholder="Une description courte du tournois..."
                    v-model="form.description"
                    required
                />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="col-span-2">
                <header>
                    <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Jeu du tournois</h2>
                </header>
            </div>

            <!-- Game -->
            <div class="col-span-1">
                <InputLabel for="update_game-game_id" value="Jeu" />
                <SelectInput
                    id="update_game-game_id"
                    class="mt-1 block w-full"
                    v-model="form.game_id"
                    placeholder="Choisir un jeu ..."
                    :data="props.games"
                    required
                />
                <InputError class="mt-2" :message="form.errors.game_id" />
            </div>

            <!-- Places -->
            <div class="col-span-1">
                <InputLabel for="update_game-places" value="Nombre de places du tournois" />
                <TextInput
                    id="update_game-places"
                    class="mt-1 block w-full"
                    type="number"
                    v-model="form.places"
                    min="1"
                    required
                />
                <InputError class="mt-2" :message="form.errors.places" />
            </div>

            <div class="col-span-1 flex items-center gap-4">
                <SubmitButton :form="form">
                    Mettre à jour
                </SubmitButton>
            </div>
        </form>
    </section>
</template>