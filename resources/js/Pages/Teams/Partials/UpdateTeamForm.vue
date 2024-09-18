<script setup>
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import TextareaInput from "@/Components/Forms/TextareaInput.vue";
import { useForm } from "@inertiajs/vue3";
import Col from "@/Components/Ui/Col.vue";

const props = defineProps({
    team: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.team.name,
    description: props.team.description,
});

const updateTeam = () => {
    form.put(route("teams.update", props.team.id), {
        preserveScroll: true,
        onSuccess: () => {},
    });
};
</script>

<template>
    <section>
        <form @submit.prevent="updateTeam" class="mt-6 grid grid-cols-2 gap-4">
            <!-- Name -->
            <Col size="2">
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
            </Col>

            <!-- Description -->
            <Col size="2">
                <InputLabel for="update-description" value="Description" />
                <TextareaInput
                    id="update-description"
                    class="mt-1 block w-full"
                    type="text"
                    :placeholder="__('Short description of the team') + '...'"
                    v-model="form.description"
                    required
                />
                <InputError class="mt-2" :message="form.errors.description" />
            </Col>

            <Col size="2" class="flex justify-end">
                <SubmitButton :form="form" color="success">
                    Mettre à jour
                </SubmitButton>
            </Col>
        </form>
    </section>
</template>
