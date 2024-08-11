<script setup>
import { onMounted, ref } from 'vue'
import SvgIcon from '@/Components/Ui/SvgIcon.vue';
import axios from 'axios';
import StatCard from '@/Components/Ui/StatCard.vue';
import ApexCharts from 'apexcharts';

const props = defineProps({
});

const chartId = "tournamentsFilling";
const display = ref(true);

onMounted(() => {    
    axios.get(route('stats.tournamentsfilling.api'))
    .then(({data}) => {
        data.data.series.length > 0 ? display.value = true : display.value = false;
        
        const chart = new ApexCharts(document.getElementById(chartId), chartOptions(data.data));
        chart.render();        
    })
});

const chartOptions = (options) => {
    return {
        series: options.series,
        labels: options.labels,
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: true,
            position: "bottom",
            fontFamily: "Inter, sans-serif",
        },
        chart: {
            width: "100%",
            type: "radialBar",
        },
        stroke: {
            colors: ["transparent"],
            lineCap: "",
        },
        plotOptions: {
            radialBar: {
                dataLabels: {
                    show: false,
                },
                hollow: {
                    margin: 0,
                    size: "32%",
                }
            },
        },
        grid: {
            padding: {
                top: -2,
            },
        },
        tooltip: {
            enabled: true,
            x: {
                show: false,
            },
        },
        yaxis: {
            show: false,
            labels: {
                formatter: function (value) {
                    return value + '%';
                }
            }
        }
    }
}

</script>

<template>
    <!-- <StatCard
        icon="trophy"
        color="red"
        title="Remplissage des tournois"
        :data="stat"
    /> -->
    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <div class="flex justify-start items-center mb-4 xl:mb-2">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Remplissage des tournois</h5>
        </div>
    
        <div :id="chartId" v-if="display"></div>
        <div v-else>
            <p>Pas de tournois ouvert</p>
        </div>
    </div>
</template>
