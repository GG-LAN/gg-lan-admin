<script setup>
import DangerButton from "@/Components/Forms/DangerButton.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import Modal from "@/Components/Forms/Modal.vue";
import SecondaryButton from "@/Components/Forms/SecondaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Supprimer le compte
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Une fois que votre compte est supprimé, toutes les informations
                associées seront définitivement perdues.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion"
            >Supprimer le compte</DangerButton
        >

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <template #header>
                Êtes-vous sûr de vouloir supprimer ce compte ?
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Une fois que votre compte est supprimé, toutes les
                    informations associées seront définitivement perdues.
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Merci de renseigner votre mot de passe pour confirmer la
                    suppression du compte.
                </p>
            </template>
            <template #body>
                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Mot de passe"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Mot de passe"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Annuler
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Supprimer le compte
                    </DangerButton>
                </div>
            </template>
        </Modal>
    </section>
</template>
