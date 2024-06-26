<script setup>
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import SubmitButton from '@/Components/Forms/SubmitButton.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';
import { useForm } from '@inertiajs/vue3';
import { FwbFileInput } from 'flowbite-vue'

const props = defineProps({
    tournament: {
        type: Object,
        required: true
    }
});

const form = useForm({
    image: null,
});

const submit = () => {
    form.post(route("tournaments.updateImage", props.tournament.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
        }
    })
}

</script>

<template>
    <div v-if="props.tournament.image" class="col-span-1 grid grid-cols-2 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <div class="col-span-2 flex justify-center items-center">
            <img class="max-w-full rounded-lg" :src="$page.props.tournament.image" :alt="$page.props.tournament.name">
        </div>
        
        <form @submit.prevent="submit" enctype='multipart/form-data' class="col-span-2 grid grid-cols-2 flex justify-items-center items-center mt-4 gap-4">
            <div class="col-span-1">
                <input 
                    class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="create-image" 
                    type="file" 
                    @input="form.image = $event.target.files[0]" 
                />
                <InputError class="mt-2" :message="form.errors.image" />
            </div>
    
            <div class="col-span-1">
                <SubmitButton :form="form">
                    Mettre à jour
                </SubmitButton>
            </div>
        </form>
    </div>

    <div v-else class="col-span-1 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <form @submit.prevent="submit" enctype='multipart/form-data'>
            <fwb-file-input v-model="form.image" dropzone class="h-64">
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
</template>