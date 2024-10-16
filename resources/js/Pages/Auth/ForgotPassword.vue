<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

defineOptions({ layout: GuestLayout });

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Vous avez oublié votre mot de passe ? Pas de soucis. Renseignez l'email
        de votre compte et nous vous enverrons un mail pour réinitialiser votre
        mot de passe.
    </div>

    <div
        v-if="status"
        class="mb-4 font-medium text-sm text-green-600 dark:text-green-400"
    >
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
            />

            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <SubmitButton :form="form" color="success">
                {{ __("Send") }}
            </SubmitButton>
        </div>
    </form>
</template>
