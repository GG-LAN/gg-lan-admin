<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    required: true
});

const props = defineProps({
    data: {
        type: Array,
    }
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        class="
            bg-gray-50
            border
            border-gray-300
            text-gray-900
            sm:text-sm
            rounded-lg
            focus:ring-primary-500
            focus:border-primary-500
            block
            w-full
            p-2.5
            dark:bg-gray-700
            dark:border-gray-600
            dark:placeholder-gray-400
            dark:text-white
            dark:focus:ring-primary-500
            dark:focus:border-primary-500
        "
        v-model="model"
        ref="input"
    >
        <option :value="item.id" v-for="item in props.data"> {{ item.name }}</option>
    </select>
</template>
