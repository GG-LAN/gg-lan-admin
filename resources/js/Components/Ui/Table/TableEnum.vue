<script setup>
import { computed } from "vue";

const props = defineProps({
    column: {
        type: Object,
        required: true,
    },
    value: {
        type: String,
        required: true,
    },
});

const enumValue = computed(() =>
    props.column.enums[props.value]
        ? props.column.enums[props.value]
        : props.value
);
</script>

<template>
    <span>{{ __(enumValue) }}</span>
</template>
