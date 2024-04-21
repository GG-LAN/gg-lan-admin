<script setup>
import { onMounted } from 'vue'
import axios from 'axios';
import ApexCharts from 'apexcharts';

let chart;

const props = defineProps({
    id: {
        type: String,
        required: true
    },
    route: {
        type: String,
        required: true
    }
});

onMounted(() => {
    var options = {
        series: [{
            data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: [
                'South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy',
                'France', 'Japan', 'United States', 'China', 'Germany'
            ],
        }
    };

    let mainChartColors = {
        borderColor: '#374151',
        labelColor: '#9CA3AF',
        opacityFrom: 0,
        opacityTo: 0.15,
    };

    axios.get(props.route)
    .then(({ data }) => {
        console.log(data);
        console.log(options);
        chart = new ApexCharts(document.querySelector("#" + props.id), data)
        chart.render();
    })
});


</script>

<template>
    <div :id="id"></div>
    
    <!-- Main widget -->
    <!-- <div class="p-4 bg-white sm:p-6 dark:bg-gray-800">
        <h3 class="text-base font-light text-gray-500 dark:text-gray-400">Sales this week</h3>
        <span class="text-xl font-bold leading-none text-gray-900 sm:text-xl dark:text-white">Stockage</span>
        <div id="test"></div>
    </div> -->
</template>