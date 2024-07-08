<script setup>

const props = defineProps({
    registrationState: {
        type: String,
    }
});

</script>

<template>
    <span v-if="props.registrationState == 'registered'" class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-green-900 dark:text-green-300" >
        Inscrite
    </span>
    <span v-else-if="props.registrationState == 'not_full'" class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-red-900 dark:text-red-300">
        Incomplète
    </span>
    <span v-else class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
        En attente
    </span>
</template>