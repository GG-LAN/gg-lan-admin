<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import { FwbFileInput } from 'flowbite-vue'
import SubmitButton from '@/Components/Forms/SubmitButton.vue';
import DangerButton from '@/Components/Forms/DangerButton.vue';

const props = defineProps({
});

const settingKey = "image_cover";

const page = usePage();
let imageCover = page.props.settings.find(element => element.key == settingKey);

const form = useForm({
    key: settingKey,
    value: "cover",
    image: null
});


const submit = () => {
    form.post(route('settings.update.image'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();

        },
    })
}
</script>

<template>
    <section class="grid grid-cols-1 gap-4">
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-2">
                Image de couverture
            </h2>
        </header>

    
        <form @submit.prevent="submit" enctype='multipart/form-data' class="col-span-1">
            <div>
                <InputLabel :for="'update_' + settingKey" value="Image" />
                <fwb-file-input v-model="form.image" class="mb-4" id="update-image">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        PNG, JPG, JPEG ou GIF (MAX. 2Mo).
                    </p>
                </fwb-file-input>
                <InputError class="mt-2" :message="form.errors.image" />
            </div>

            <div class="flex items-center gap-4">
                <SubmitButton :form="form">
                    Mettre à jour
                </SubmitButton>
            </div>
        </form>


        <div class="col-span-1" v-if="imageCover">
            <img class="w-1/2 rounded-lg hover:grayscale transition-all duration-300" :src="imageCover.value">
        </div>
    </section>

</template>