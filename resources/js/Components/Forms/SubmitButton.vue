<script setup>
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import PrimaryButton from '@/Components/Forms/PrimaryButton.vue'
import SuccessButton from '@/Components/Forms/SuccessButton.vue'
import { FwbToast } from 'flowbite-vue'

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    color: {
        type: String,
        default: "primary"
    },
    successMessage: {
        type: String,
        default: "Mise à jour réussite !"
    }
});
</script>

<template>
    <PrimaryButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }" type="submit" v-if="color == 'primary'">
        <span><slot/></span>
        <SvgIcon class="ml-2 w-4 h-4" v-if="form.processing" icon="sync" :loading="form.processing" />
    </PrimaryButton>

    <SuccessButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }" type="submit" v-if="color == 'success'">
        <span><slot/></span>
        <SvgIcon class="ml-2 w-4 h-4" v-if="form.processing" icon="sync" :loading="form.processing" />
    </SuccessButton>
    


    <Transition
        enter-active-class="transition ease-in-out duration-300"
        enter-from-class="opacity-0"
        leave-active-class="transition ease-in-out duration-300"
        leave-to-class="opacity-0"
    >
        <!-- <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Mis à jour !</p> -->
        <fwb-toast type="success" v-if="form.recentlySuccessful" class="fixed top-20 right-7 !bg-green-800 !text-white">
            <span class="text-sm font-medium">
                {{ props.successMessage}}
            </span>
        </fwb-toast>
    </Transition>
</template>
