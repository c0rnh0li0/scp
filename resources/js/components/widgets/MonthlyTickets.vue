<template>
    <v-card outlined elevation="1">
        <v-toolbar color="blue darken-1" dark dense elevation="1">
            <v-icon>mdi-card-plus</v-icon>
            <v-card-title>
                {{ $t('message.widgets.monthlytickets.title') }}
            </v-card-title>
        </v-toolbar>
        <v-card-text class="pa-2">
            <bar-chart :chart-data="chartdata" :options="options"></bar-chart>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-card-subtitle>
                {{ $t('message.widgets.monthlytickets.footer') }}
            </v-card-subtitle>
        </v-card-actions>
    </v-card>
</template>

<script>
    import BarChart from '../custom/charts/BarChart'

    export default {
        name: "MonthlyTickets",
        components: {
            BarChart
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
                    let ticketsData = await axios.get('/api/monthlytickets')

                    this.chartdata = {
                        labels: ticketsData.data.chartdata.labels,
                        datasets: [
                            {
                                label: this.$t('message.widgets.monthlytickets.total_tickets_lbl'),
                                data: ticketsData.data.chartdata.datasets.all.data,
                                backgroundColor: '#42A5F5',
                                //barPercentage: 0.4
                            },
                            {
                                label: this.$t('message.widgets.monthlytickets.used_tickets_lbl'),
                                data: ticketsData.data.chartdata.datasets.used.data,
                                backgroundColor: '#4DB6AC',
                                //barPercentage: 0.4
                            }
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