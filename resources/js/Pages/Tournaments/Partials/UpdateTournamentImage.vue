<script setup>
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SelectInput from "@/Components/Forms/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import { FwbFileInput } from "flowbite-vue";
import DangerButton from "@/Components/Forms/DangerButton.vue";
import Col from "@/Components/Ui/Col.vue";

const props = defineProps({
    tournament: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    image: null,
});

const formDelete = useForm({});

const submit = () => {
    form.post(route("tournaments.updateImage", props.tournament.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const deleteImage = () => {
    formDelete.post(route("tournaments.deleteImage", props.tournament.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            formDelete.reset();
        },
    });
};
</script>

<template>
    <div v-if="props.tournament.image">
        <form
            @submit.prevent="submit"
            enctype="multipart/form-data"
            class="grid grid-cols-1 space-y-2"
        >
            <Col size="1" class="flex justify-center items-center oui">
                <InputLabel for="update-image">
                    <img
                        class="max-w-full rounded-lg cursor-pointer hover:grayscale transition-all duration-300"
                        :src="$page.props.tournament.image"
                        :alt="$page.props.tournament.name"
                    />
                </InputLabel>

                <input
                    class="hidden"
                    id="update-image"
                    type="file"
                    @input="form.image = $event.target.files[0]"
                />
            </Col>

            <Col
                size="1"
                class="overflow-hidden truncate hover:text-clip"
                v-if="form.image"
            >
                <span>
                    <span class="font-bold">Fichier: </span>
                    {{ form.image.name }}
                </span>
            </Col>

            <InputError :message="form.errors.image" />

            <div class="col-span-1 flex items-center justify-between gap-4">
                <DangerButton
                    :loading="formDelete.processing"
                    :disabled="formDelete.processing"
                    :class="{ 'opacity-25': formDelete.processing }"
                    @click="deleteImage"
                >
                    Supprimer image
                </DangerButton>

                <SubmitButton :form="form" color="success">
                    {{ __("Update") }}
                </SubmitButton>
            </div>
        </form>
    </div>

    <Col size="1" v-else>
        <form
            @submit.prevent="submit"
            enctype="multipart/form-data"
            class="space-y-4"
        >
            <fwb-file-input
                v-model="form.image"
                dropzone
                class="h-64 truncate"
                id="update-image"
            >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    PNG, JPG, JPEG, GIF, WEBP (MAX. 2Mo).
                </p>
            </fwb-file-input>

            <InputError :message="form.errors.image" />

            <div class="flex items-center justify-end">
                <SubmitButton :form="form" color="success">
                    {{ __("Update") }}
                </SubmitButton>
            </div>
        </form>
    </Col>
</template>
