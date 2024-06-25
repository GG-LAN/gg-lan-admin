<script setup>
import SuccessButton from '@/Components/Forms/SuccessButton.vue';
// import { router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    tournament: {
        type: Object,
    }
});

let form = useForm({});

const openTournament = () => {
    form.post(route('tournaments.openTournament', props.tournament.id), {
        preserveState: true,
        preserveScroll: true
    })
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-black text-gray-900 dark:text-gray-100">Ouvrir le tournois</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Cliquez sur le bouton pour ouvrir le tournois et permettre aux joueurs ou équipes de s'y inscrire.
            </p>
        </header>

        <SuccessButton @click="openTournament" :disabled="form.processing" :class="{ 'opacity-25': form.processing }">Ouvrir le tournois</SuccessButton>
    </section>
</template>
