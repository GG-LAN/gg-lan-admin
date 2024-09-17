<script setup>
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import ToggleInput from "@/Components/Forms/ToggleInput.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import { useForm } from "@inertiajs/vue3";
import Col from "@/Components/Ui/Col.vue";

const props = defineProps({
    player: {
        type: Object,
    },
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
        onSuccess: () => {},
    });
};
</script>

<template>
    <section>
        <form
            @submit.prevent="updatePlayer"
            class="mt-6 grid grid-cols-2 gap-4"
        >
            <Col size="2">
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
            </Col>

            <Col size="2">
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
            </Col>

            <Col size="2">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    autocomplete="email"
                />

                <InputError :message="form.errors.email" class="mt-2" />
            </Col>

            <Col size="1">
                <InputLabel for="admin" value="Admin" />

                <ToggleInput id="admin" v-model:checked="form.admin" />

                <InputError class="mt-2" :message="form.errors.admin" />
            </Col>

            <Col size="1" class="flex items-center justify-end">
                <SubmitButton :form="form" color="success">
                    {{ __("Update") }}
                </SubmitButton>
            </Col>
        </form>
    </section>
</template>
