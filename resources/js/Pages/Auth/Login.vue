<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Checkbox from '@/Components/Forms/Checkbox.vue';
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import PrimaryButton from '@/Components/Forms/PrimaryButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineOptions({layout: GuestLayout});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
        Connexion
    </h2>
    <form @submit.prevent="submit" class="mt-8 space-y-6">
        <!-- Email -->
        <div>
            <InputLabel for="email" value="Email"/>
            <TextInput
                id="email"
                type="email"
                v-model="form.email"
                class="mt-1 block w-full"
                required
                autofocus
                placeholder="name@example.com"
                autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <InputLabel for="password" value="Mot de passe" />

            <TextInput
                id="password"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password"
                required
                placeholder="••••••••"
                autocomplete="current-password"
            />

            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="flex items-start">
            <!-- Remember Me -->
            <div class="flex items-center h-5">
                <Checkbox name="remember" id="remember" aria-describedby="remember" v-model:checked="form.remember"/>
                <label for="remember" class="ms-2 text-sm text-gray-600 dark:text-gray-400">Se souvenir de moi</label>
            </div>

            <!-- Forgot Password -->
            <Link :href="route('password.request')" class="ml-auto text-sm font-medium text-primary-700 hover:underline dark:text-primary-500">
                Mot de passe oublié?
            </Link>
        </div>

        <!-- Log in -->
        <PrimaryButton class="px-5 py-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
            Se connecter
        </PrimaryButton>
    </form>
</template>
