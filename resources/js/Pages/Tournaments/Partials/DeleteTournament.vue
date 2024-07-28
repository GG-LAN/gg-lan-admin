<script setup>
import SecondaryButton from '@/Components/Forms/SecondaryButton.vue';
import SubmitButton from '@/Components/Forms/SubmitButton.vue';
import DangerButton from '@/Components/Forms/DangerButton.vue';
import Modal from '@/Components/Forms/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tournament: {
        type: Object,
    }
});

const confirmingTournamentDeletion = ref(false);

let form = useForm({});

const confirmTournamentDeletion = () => {
    confirmingTournamentDeletion.value = true;
};

const deleteTournament = () => {
    form.delete(route('tournaments.destroy', props.tournament.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingTournamentDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6 text-center">
        <Modal :show="confirmingTournamentDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Êtes-vous sûr de vouloir supprimer ce tournois ?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Une fois supprimé, toutes les informations associées seront définitivement perdues.
                </p>

                <form @submit.prevent="deleteTournament">
                    <div class="mt-6 flex justify-center">
                        <SubmitButton :form="form" color="danger" successMessage="Le tournois a bien été supprimé !">
                            Supprimer le tournois
                        </SubmitButton>

                        <SecondaryButton @click="closeModal" class="ml-4"> Annuler </SecondaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <DangerButton @click="confirmTournamentDeletion">
            Supprimer le tournois
        </DangerButton>
    </section>
</template>
