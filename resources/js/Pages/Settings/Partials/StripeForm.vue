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

const page = usePage();
const isShowKey = ref(false);
let stripeKey = page.props.settings.find(element => element.key == "stripe_api_key");

const form = useForm({
    key: "stripe_api_key",
    value: stripeKey ? stripeKey.value : ""
});

const submit = () => {
    form.put(route('settings.update'), {
        preserveScroll: true,
        preserveState: true,
    })
}

const showKey = () => {
    isShowKey.value = !isShowKey.value;
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-2">
                Configuration Stripe
                <a href="https://dashboard.stripe.com/dashboard" target="_blank" class="text-blue-500 text-sm text-base hover:underline">
                    <SvgIcon class="ml-2" icon="arrow-up-right-from-square"/>
                    Stripe
                </a>
            </h2>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div>
                <InputLabel for="update_stripe_key" value="Clé Stripe" />
                <div class="relative w-full">
                    <button type="button" @click="showKey" class="absolute inset-y-0 end-0 flex items-center pe-3">
                        <SvgIcon class="h-5 w-5 text-gray-500 dark:text-gray-400" icon="eye" v-if="!isShowKey"/>
                        <SvgIcon class="h-5 w-5 text-gray-500 dark:text-gray-400" icon="eye-slash" v-else/>
                    </button>
                    <TextInput
                        id="update_stripe_key"
                        class="mt-1 block w-full pe-10"
                        :type="isShowKey ? 'text' : 'password'"
                        placeholder="ex: sk_test_cNfGvl6MqTzetsdlZGp0hWb3m"
                        v-model="form.value"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.value" />
            </div>

            <div class="flex items-center gap-4">
                <SubmitButton :form="form">
                    Mettre à jour
                </SubmitButton>
            </div>
        </form>
        
        <p class="mt-2">Ceci est une clé confidentielle !</p>
    </section>

</template>