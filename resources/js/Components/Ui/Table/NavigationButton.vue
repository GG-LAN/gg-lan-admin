<script setup>
import { Link } from '@inertiajs/vue3';
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import AlternativeButton from "@/Components/Forms/AlternativeButton.vue"
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue"

const props = defineProps({
    icon: {
        type: String,
        required: true
    },
    link: {
        required: true
    }
});
</script>

<template>
    <Link :href="link" v-if="link" preserve-scroll>
        <AlternativeButton :icon="icon"/>
    </Link>

    <AlternativeButton :icon="icon" class="cursor-not-allowed" disabled v-else/>
</template>