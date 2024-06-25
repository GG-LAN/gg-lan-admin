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
    status: {
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
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informations du tournois</h2>
        </header>

        <form @submit.prevent="updateTournament" class="mt-6 space-y-6">
            <!-- Name -->
            <div>
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

            <!-- Description -->
            <div>
                <InputLabel for="update-description" value="Description" />
                <TextInput
                    id="update-description"
                    class="mt-1 block w-full"
                    type="text"
                    v-model="form.description"
                    required
                />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <!-- Cashprize -->
            <div>
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

            <!-- Status -->
            <div>
                <InputLabel for="update-status" value="Statut" />
                <SelectInput
                    id="update-status"
                    class="mt-1 block w-full"
                    v-model="form.status"
                    :data="status"
                    required
                />
                <InputError class="mt-2" :message="form.errors.status" />
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