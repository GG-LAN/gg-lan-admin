<script setup>
import SecondaryButton from "@/Components/Forms/SecondaryButton.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import DangerButton from "@/Components/Forms/DangerButton.vue";
import Modal from "@/Components/Forms/Modal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();

const show = ref(false);

let form = useForm({});

const deleteTeam = () => {
    form.delete(route("teams.destroy", page.props.team.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const showModal = () => (show.value = true);

const closeModal = () => (show.value = false);
</script>

<template>
    <section class="space-y-6 text-center">
        <Modal :show="show" @close="closeModal">
            <template #header>
                {{ __("Are you sure you want to delete this team ?") }}

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{
                        __(
                            "Once deleted, all related informations will also be deleted."
                        )
                    }}
                </p>
            </template>
            <template #body>
                <div class="flex justify-end">
                    <SecondaryButton @click="closeModal" class="mr-4">
                        {{ __("Cancel") }}
                    </SecondaryButton>

                    <form @submit.prevent="deleteTeam">
                        <SubmitButton :form="form" color="danger">
                            {{ __("Delete Team") }}
                        </SubmitButton>
                    </form>
                </div>
            </template>
        </Modal>

        <DangerButton @click="showModal">
            {{ __("Delete Team") }}
        </DangerButton>
    </section>
</template>
