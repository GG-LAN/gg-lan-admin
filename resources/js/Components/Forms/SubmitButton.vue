<script setup>
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import PrimaryButton from '@/Components/Forms/PrimaryButton.vue'

const props = defineProps({
    form: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <PrimaryButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }" type="submit">
        <span><slot/></span>
        <SvgIcon class="ml-2 w-4 h-4" v-if="form.processing" icon="sync" :loading="form.processing" />
    </PrimaryButton>


    <Transition
        enter-active-class="transition ease-in-out"
        enter-from-class="opacity-0"
        leave-active-class="transition ease-in-out"
        leave-to-class="opacity-0"
    >
        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Mis à jour !</p>
    </Transition>
</template>
