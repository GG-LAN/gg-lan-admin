<script setup>
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import SubmitButton from '@/Components/Forms/SubmitButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
// import SelectInput from '@/Components/Forms/SelectInput.vue';
import TextareaInput from '@/Components/Forms/TextareaInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    team: {
        type: Object,
        required: true
    }
});

const form = useForm({
    name:        props.team.name,
    description: props.team.description,
});

const updateTeam = () => {
    form.put(route("teams.update", props.team.id), {
        preserveScroll: true,
        onSuccess: () => {
        }
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Informations de l'équipe</h2>
        </header>

        <form @submit.prevent="updateTeam" class="mt-6 grid grid-cols-2 gap-4">
            <!-- Name -->
            <div class="col-span-1">
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
            <div class="col-span-2">
                <InputLabel for="update-description" value="Description" />
                <TextareaInput
                    id="update-description"
                    class="mt-1 block w-full"
                    type="text"
                    placeholder="Une description courte du tournois..."
                    v-model="form.description"
                    required
                />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="col-span-1 flex items-center gap-4">
                <SubmitButton :form="form">
                    Mettre à jour
                </SubmitButton>
            </div>
        </form>
    </section>
</template>