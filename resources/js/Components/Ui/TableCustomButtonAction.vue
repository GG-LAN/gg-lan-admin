<script setup>
import DangerButton from "@/Components/Forms/DangerButton.vue"
import SuccessButton from "@/Components/Forms/SuccessButton.vue"
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue"
import SubmitButton from "@/Components/Forms/SubmitButton.vue"
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    customAction: {
        type: Object,
        required: true
    },
    row: {
        type: Object,
        required: true
    }
})

const form = useForm({});

const show = computed(() => 
    eval(props.customAction.condition) ? true : false
);

const type = computed(() => 
    props.customAction.type
);

const icon = computed(() => 
    props.customAction.icon
);

const addDynamicFieldsToForm = () => {
    const keys = Object.keys(props.row);
    for (let index = 0; index < keys.length; index++) {
        let key = keys[index];
        let value = props.row[key];

        form[key] = value;
    }
};

const dynamicFields = () => {
    const keys = Object.keys(props.row);
    const data = {};

    for (let index = 0; index < keys.length; index++) {
        let key = keys[index];

        data[key] = form[key];
    }

    return data;
};

addDynamicFieldsToForm()

const submit = () => {
    form.transform((data) => ({
        ...dynamicFields()
    }))
    .post(route(props.customAction.route), {
        preserveScroll: true,
        preserveState: true
    })
};
</script>

<template>
    <form @submit.prevent="submit">
        <SubmitButton :form="form" :color="type" :icon="icon" v-if="show" />
    </form>
    <!-- <PrimaryButton :icon="icon" @click="submit" v-if="type == 'primary' && show"/>

    <DangerButton :icon="icon" @click="submit" v-if="type == 'danger' && show"/>

    <SuccessButton :icon="icon" @click="submit" v-if="type == 'success' && show"/> -->
</template>
