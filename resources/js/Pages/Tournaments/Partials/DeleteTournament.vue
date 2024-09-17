<script setup>
import SecondaryButton from "@/Components/Forms/SecondaryButton.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import DangerButton from "@/Components/Forms/DangerButton.vue";
import Modal from "@/Components/Forms/Modal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();

const confirmingTournamentDeletion = ref(false);

let form = useForm({});

const confirmTournamentDeletion = () => {
    confirmingTournamentDeletion.value = true;
};

const deleteTournament = () => {
    form.delete(route("tournaments.destroy", page.props.tournament.id), {
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
            <template #header>
                {{ __("Are you sure you want to delete this tournament ?") }}

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Une fois supprimé, toutes les informations associées seront
                    définitivement perdues.
                </p>
            </template>
            <template #body>
                <div class="flex justify-end">
                    <SecondaryButton @click="closeModal" class="mr-4">
                        {{ __("Cancel") }}
                    </SecondaryButton>

                    <form @submit.prevent="deleteTournament">
                        <SubmitButton :form="form" color="danger">
                            {{ __("Delete tournament") }}
                        </SubmitButton>
                    </form>
                </div>
            </template>
        </Modal>

        <DangerButton @click="confirmTournamentDeletion">
            Supprimer le tournois
        </DangerButton>
    </section>
</template>
