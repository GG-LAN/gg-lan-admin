<script setup>
import { onMounted, ref } from 'vue'
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import axios from 'axios';
import StatCard from '@/Components/Ui/StatCard.vue';

const props = defineProps({
});

const stat = ref('...');

onMounted(() => {
    axios.get(route('stats.teams.api'))
    .then(({data}) => {
        stat.value = data.data;
        
    })
});

</script>

<template>
    <StatCard
        icon="users"
        color="yellow"
        title="Équipes"
        :data="stat"
    />
</template>