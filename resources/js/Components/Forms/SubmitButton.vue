<script setup>
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import PrimaryButton from '@/Components/Forms/PrimaryButton.vue';
import SuccessButton from '@/Components/Forms/SuccessButton.vue';
import DangerButton from '@/Components/Forms/DangerButton.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    color: {
        type: String,
        default: "primary"
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

    <DangerButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }" type="submit" v-if="color == 'danger'">
        <span><slot/></span>
        <SvgIcon class="ml-2 w-4 h-4" v-if="form.processing" icon="sync" :loading="form.processing" />
    </DangerButton>
</template>
