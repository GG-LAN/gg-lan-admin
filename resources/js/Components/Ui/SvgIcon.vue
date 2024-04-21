<script setup>

const props = defineProps({
    icon: {
        type: String,
        required: true
    },
    loading: {
        type: Boolean,
        default: false
    }
});

</script>

<template>
    <font-awesome-icon :icon="['fas', icon]" fill="currentColor" v-if="!loading"/>
    <font-awesome-icon :icon="['fas', 'sync']" fill="currentColor" spin v-else/>
</template>
