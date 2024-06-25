<script setup>
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import PrimaryButton from '@/Components/Forms/PrimaryButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    tournament: {
        type: Object,
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
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Dates du tournois</h2>
        </header>

        <form @submit.prevent="updateTournament" class="mt-6 space-y-6">
            <!-- Start Date -->
            <div>
                <InputLabel for="update_date-start_date" value="Date de début" />
                <TextInput
                    id="update_date-start_date"
                    class="mt-1 block w-full"
                    type="date"
                    v-model="form.start_date"
                    required
                />
                <InputError class="mt-2" :message="form.errors.start_date" />
            </div>

            <!-- End Date -->
            <div>
                <InputLabel for="update_date-end_date" value="Date de fin" />
                <TextInput
                    id="update_date-end_date"
                    class="mt-1 block w-full"
                    type="date"
                    v-model="form.end_date"
                    required
                />
                <InputError class="mt-2" :message="form.errors.end_date" />
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