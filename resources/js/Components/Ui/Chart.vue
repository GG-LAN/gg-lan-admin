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
    }
});

onMounted(() => {
    const options = {
        series: [35.1, 23.5, 2.4, 5.4],
        colors: ["#1C64F2", "#16BDCA", "#FDBA8C", "#E74694"],
        labels: ["Direct", "Sponsor", "Affiliate", "Email marketing"],
        dataLabels: {
            enabled: false,
        },
        legend: {
            position: "bottom",
            fontFamily: "Inter, sans-serif",
        },
        chart: {
            height: 320,
            width: "100%",
            type: "donut",
        },
        stroke: {
            colors: ["transparent"],
            lineCap: "",
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontFamily: "Inter, sans-serif",
                            offsetY: 20,
                        },
                        total: {
                            showAlways: true,
                            show: true,
                            label: "Unique visitors",
                            fontFamily: "Inter, sans-serif",
                            formatter: function (w) {
                                const sum = w.globals.seriesTotals.reduce((a, b) => {
                                    return a + b
                                }, 0)
                                return '$' + sum + 'k'
                            },
                        },
                        value: {
                            show: true,
                            fontFamily: "Inter, sans-serif",
                            offsetY: -20,
                            formatter: function (value) {
                                return value + "k"
                            },
                        },
                    },
                    size: "80%",
                },
            },
        },
        grid: {
            padding: {
                top: -2,
            },
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return value + "k"
                },
            },
        },
        xaxis: {
            labels: {
                formatter: function (value) {
                    return value  + "k"
                },
            },
            axisTicks: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
        },
    }

    const chart = new ApexCharts(document.getElementById(props.id), options);
    chart.render();

    // axios.get(props.route)
    // .then(({ data }) => {
    //     console.log(data);
    //     console.log(options);
    //     chart = new ApexCharts(document.querySelector("#" + props.id), data)
    //     chart.render();
    // })
});


</script>

<template>
    <div class="p-4 bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <div class="flex justify-start items-center mb-4 xl:mb-2">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Website traffic</h5>
    
            <!-- <svg data-popover-target="chart-info" data-popover-placement="bottom" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z"/>
            </svg> -->
    
            <!-- <div data-popover id="chart-info" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                <div class="p-3 space-y-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Activity growth - Incremental</h3>
                    <p>Report helps navigate cumulative growth of community activities. Ideally, the chart should have a growing trend, as stagnating chart signifies a significant decrease of community activity.</p>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Calculation</h3>
                    <p>For each date bucket, the all-time volume of activities is calculated. This means that activities in period n contain all activities up to period n, plus the activities generated by your community in period.</p>
                </div>
                <div data-popper-arrow></div>
            </div> -->
        </div>
    
        <div :id="id"></div>
    </div>
</template>