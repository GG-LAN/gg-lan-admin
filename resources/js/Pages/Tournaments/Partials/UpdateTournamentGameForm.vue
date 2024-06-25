<script setup>
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import PrimaryButton from '@/Components/Forms/PrimaryButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    tournament: {
        type: Object,
        required: true
    },
    games: {
        type: Array,
        required: true
    },
    types: {
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
    status:      props.tournament.status,
    type:        props.tournament.type,
});

const imageForm = useForm({
    image: null,
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
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Jeu du tournois</h2>
        </header>

        <form @submit.prevent="updateTournament" class="mt-6 space-y-6">
            <!-- Game -->
            <div>
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

            <!-- Type -->
            <div>
                <InputLabel for="update_game-type" value="Type" />
                <SelectInput
                    id="update_game-type"
                    class="mt-1 block w-full"
                    v-model="form.type"
                    :data="props.types"
                    required
                />
                <InputError class="mt-2" :message="form.errors.type" />
            </div>

            <!-- Places -->
            <div>
                <InputLabel for="update_game-places" value="Nombre de places" />
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

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" type="submit">Mettre à jour</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Mis à jour !</p>
                </Transition>
            </div>
        </form>
    </section>
</template>