<template>
    <v-card outlined elevation="1">
        <v-toolbar color="yellow darken-3" dark dense elevation="1">
            <v-icon>mdi-card-text</v-icon>
            <v-card-title>
                This year's tickets
            </v-card-title>
        </v-toolbar>
        <v-card-text class="pa-2">
            <line-chart :chart-data="chartdata" :options="options"></line-chart>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-card-subtitle>
                Tickets flow this year
            </v-card-subtitle>
        </v-card-actions>
    </v-card>
</template>

<script>
    import LineChart from '../custom/charts/LineChart'

    export default {
        name: "YearlyTickets",
        components: {
            LineChart
        },
        data: () => ({
            loaded: false,
            chartdata: null,
            options: {
                title: {
                    display: false,
                    //text: 'Chart.js Bar Chart - Stacked'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                defaultFontFamily: "'Roboto'",
                responsive: true,
                responsiveAnimationDuration: 0.5,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        stacked: false,
                        gridLines: {
                            offsetGridLines: true
                        },
                        display: true,
                    }],
                    yAxes: [{
                        stacked: false,
                        gridLines: {
                            offsetGridLines: true
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        },
                        display: true,
                    }]
                }
            }
        }),
        methods: {
            async getChartData() {
                try {
                    let ticketsData = await axios.get('/api/yearlytickets')

                    this.chartdata = {
                        labels: ticketsData.data.chartdata.labels,
                        datasets: [
                            {
                                label: 'Total tickets',
                                data: ticketsData.data.chartdata.datasets.all.data,
                                backgroundColor: 'transparent',
                                borderColor: '#FDD835',
                                pointBackgroundColor: '#FDD835',
                                borderWidth: 2,
                                fill: false,
                                steppedLine: false,
                                //barPercentage: 0.4
                            },
                            {
                                label: 'Used tickets',
                                data: ticketsData.data.chartdata.datasets.used.data,
                                backgroundColor: 'transparent',
                                borderColor: '#EF6C00',
                                pointBackgroundColor: '#EF6C00',
                                borderWidth: 2,
                                fill: false,
                                steppedLine: false,
                                //barPercentage: 0.4
                            },
                        ]
                    }

                    this.loaded = true
                } catch (e) {
                    console.error(e)
                }
            }
        },
        async mounted () {
            this.loaded = false
            await this.getChartData()
        }
    }
</script>

<style scoped>

</style>