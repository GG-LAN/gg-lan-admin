<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import SubmitButton from '@/Components/Forms/SubmitButton.vue';

const props = defineProps({
});

const settingKey = "lan_location";

const page = usePage();
let lanLocation = page.props.settings.find(element => element.key == settingKey);

const form = useForm({
    key: settingKey,
    value: lanLocation ? lanLocation.value : ""
});

const submit = () => {
    form.put(route('settings.update'), {
        preserveScroll: true,
        preserveState: true,
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-2">
                Localisation de la LAN
            </h2>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div>
                <InputLabel :for="'update_' + settingKey" value="Adresse" />
                <TextInput
                    :id="'update_' + settingKey"
                    class="mt-1 block w-full pe-10"
                    type="text"
                    placeholder="123, Av. de la bagarre, 29200 Brest"
                    v-model="form.value"
                />
                <InputError class="mt-2" :message="form.errors.value" />
            </div>

            <div class="flex items-center gap-4">
                <SubmitButton :form="form">
                    Mettre à jour
                </SubmitButton>
            </div>
        </form>        
    </section>

</template>