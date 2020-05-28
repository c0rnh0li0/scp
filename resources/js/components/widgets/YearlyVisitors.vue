<template>
    <v-card outlined elevation="1">
        <v-toolbar color="indigo darken-2" dark dense elevation="1">
            <v-icon>mdi-account-heart</v-icon>
            <v-card-title>
                {{ $t('message.widgets.yearlyvisitors.title') }}
            </v-card-title>
        </v-toolbar>
        <v-card-text class="pa-2">
            <line-chart :chart-data="chartdata" :options="options"></line-chart>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-card-subtitle>
                {{ $t('message.widgets.yearlyvisitors.footer') }}
            </v-card-subtitle>
        </v-card-actions>
    </v-card>
</template>

<script>
    import LineChart from '../custom/charts/LineChart'

    export default {
        name: "YearlyVisitors",
        props: ['businessId'],
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
                    let ticketsData = await axios.get('/api/yearlyvisitors/' + (this.businessId ? this.businessId : ''))

                    this.chartdata = {
                        labels: ticketsData.data.chartdata.labels,
                        datasets: [
                            {
                                label: this.$t('message.widgets.yearlyvisitors.total_visitors_lbl'),
                                data: ticketsData.data.chartdata.datasets.all.data,
                                backgroundColor: 'transparent',
                                borderColor: '#1A237E',
                                pointBackgroundColor: '#1A237E',
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