<script setup>
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import SubmitButton from '@/Components/Forms/SubmitButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';
import { useForm } from '@inertiajs/vue3';
import { FwbFileInput } from 'flowbite-vue'
import DangerButton from '@/Components/Forms/DangerButton.vue';

const props = defineProps({
    tournament: {
        type: Object,
        required: true
    }
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
        }
    })
}

const deleteImage = () => {
    formDelete.post(route("tournaments.deleteImage", props.tournament.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            formDelete.reset();
        }
    })
}

</script>

<template>
    <section class="bg-white rounded-lg shadow-sm p-4 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-4">Image du tournois</h2>
        </header>

        <div v-if="props.tournament.image">
            <form @submit.prevent="submit" enctype='multipart/form-data' class="grid grid-cols-1 gap-4">
                <div class="col-span-1 flex justify-center items-center">
                    <InputLabel for="update-image">
                        <img class="max-w-full rounded-lg cursor-pointer hover:grayscale transition-all duration-300" :src="$page.props.tournament.image" :alt="$page.props.tournament.name">
                    </InputLabel>
    
                    <input
                        class="hidden"
                        id="update-image" 
                        type="file" 
                        @input="form.image = $event.target.files[0]" 
                    />
                </div>

                
                <div v-if="form.image" class="col-span-1 overflow-hidden truncate hover:text-clip">
                    <span>
                        <span class="font-bold">Fichier: </span>
                        {{ form.image.name }}
                    </span>
                </div>
                
                <InputError class="mt-2" :message="form.errors.image" />
                    
                <div class="col-span-1 flex items-center gap-4">
                    <SubmitButton :form="form">
                        Mettre à jour
                    </SubmitButton>

                    <DangerButton
                        :loading="formDelete.processing"
                        :disabled="formDelete.processing"
                        :class="{ 'opacity-25': formDelete.processing }"
                        @click="deleteImage"
                    >
                        Supprimer image
                    </DangerButton>
                </div>
            </form>
        </div>
    
        <div v-else class="col-span-1">
            <form @submit.prevent="submit" enctype='multipart/form-data'>
                <fwb-file-input v-model="form.image" dropzone class="h-64 truncate" id="update-image">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        PNG, JPG, JPEG ou GIF (MAX. 2Mo).
                    </p>
                </fwb-file-input>
                <InputError class="mt-2" :message="form.errors.image" />
            
                <div class="flex items-center mt-4">
                    <SubmitButton :form="form">
                        Mettre à jour
                    </SubmitButton>
                </div>
            </form>
        </div>
    </section>
</template>