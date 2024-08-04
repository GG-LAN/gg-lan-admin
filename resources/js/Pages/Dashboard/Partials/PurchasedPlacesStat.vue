<script setup>
import { onMounted, ref } from 'vue'
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import axios from 'axios';
import StatCard from '@/Components/Ui/StatCard.vue';

const props = defineProps({
});

const stat = ref('...');

onMounted(() => {
    axios.get(route('stats.payments.api'))
    .then(({data}) => {
        stat.value = data.data;
        
    })
});

</script>

<template>
    <StatCard
        icon="money-bill"
        color="green"
        title="Paiements en ligne"
        :data="stat"
    />
</template>