<script setup>
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import SubmitButton from '@/Components/Forms/SubmitButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import ToggleInput from '@/Components/Forms/ToggleInput.vue';
import Checkbox from '@/Components/Forms/Checkbox.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    player: {
        type: Object,
    }
});

const form = useForm({
    name: props.player.name,
    pseudo: props.player.pseudo,
    email: props.player.email,
    admin: props.player.admin,
});

const updatePlayer = () => {
    form.put(route("players.update", props.player.id), {
        preserveScroll: true,
        onSuccess: () => {
        }
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Informations du joueur</h2>
        </header>

        <form @submit.prevent="updatePlayer" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Nom" />

                <TextInput
                    id="name"
                    ref="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="name"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div>
                <InputLabel for="pseudo" value="Pseudo" />

                <TextInput
                    id="pseudo"
                    ref="pseudo"
                    v-model="form.pseudo"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="pseudo"
                />

                <InputError :message="form.errors.pseudo" class="mt-2" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    autocomplete="email"
                />

                <InputError :message="form.errors.email" class="mt-2" />
            </div>

            <div>
                <InputLabel for="admin" value="Admin" />

                <ToggleInput 
                    id="admin"
                    v-model:checked="form.admin" 
                />
                
                <InputError class="mt-2" :message="form.errors.admin" />
            </div>

            <div class="flex items-center gap-4">
                <SubmitButton :form="form">
                    Mettre à jour
                </SubmitButton>
            </div>
        </form>
    </section>
</template>