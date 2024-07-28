<script setup>

const props = defineProps({
    icon: {
        type: String,
        required: true
    },
    loading: {
        type: Boolean,
        default: false
    },
    library: {
        type: String,
        default: "fas"
    }
});

</script>

<template>
    <font-awesome-icon :icon="[props.library, icon]" fill="currentColor" v-if="!loading"/>
    <font-awesome-icon :icon="['fas', 'sync']" fill="currentColor" spin v-else/>
</template>
