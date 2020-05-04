<template>
    <div>
        <v-layout row wrap class="pa-2">
            <v-row dense class="mx-auto">
                <v-flex xs12 sm16 md4 lg3 xl3>
                    <v-col cols="12">
                        <today-tickets></today-tickets>
                    </v-col>
                </v-flex>
                <v-flex xs12 sm16 md4 lg3 xl3>
                    <v-col cols="12">
                        <daily-visitors></daily-visitors>
                    </v-col>
                </v-flex>
                <v-flex xs12 sm16 md4 lg3 xl3>
                    <v-col cols="12">
                        <daily-new-users></daily-new-users>
                    </v-col>
                </v-flex>
                <v-flex xs12 sm16 md4 lg3 xl3>
                    <v-col cols="12">
                        <today-places></today-places>
                    </v-col>
                </v-flex>
            </v-row>

            <v-row dense class="mx-auto">
                <v-row dense class="mx-auto">
                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <most-visited-places></most-visited-places>
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <monthly-tickets></monthly-tickets>
                        </v-col>
                    </v-flex>
                </v-row>
            </v-row>
            <v-row dense class="mx-auto">
                <v-row dense class="mx-auto">
                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <yearly-visitors></yearly-visitors>
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <yearly-tickets></yearly-tickets>
                        </v-col>
                    </v-flex>
                </v-row>
            </v-row>
        </v-layout>
    </div>
</template>

<script>
    import MonthlyTickets from './../widgets/MonthlyTickets'
    import YearlyTickets from './../widgets/YearlyTickets'
    import YearlyVisitors from './../widgets/YearlyVisitors'
    import DailyVisitors from './../widgets/DailyVisitors'
    import TodayPlaces from './../widgets/TodayPlaces'
    import TodayTickets from './../widgets/TodayTickets'
    import DailyNewUsers from './../widgets/DailyNewUsers'
    import MostVisitedPlaces from './../widgets/MostVisitedPlaces'

    export default {
        name: 'Dashboard',
        components: {
            MonthlyTickets,
            YearlyTickets,
            YearlyVisitors,
            DailyVisitors,
            TodayPlaces,
            TodayTickets,
            DailyNewUsers,
            MostVisitedPlaces
        },
        data: () => ({}),
        methods: {
            fillData () {
                this.linedatacollection = {
                    labels: [this.getRandomInt(), this.getRandomInt()],
                    datasets: [
                        {
                            label: 'Data One',
                            backgroundColor: '#f87979',
                            data: [this.getRandomInt(), this.getRandomInt()]
                        }, {
                            label: 'Data One',
                            backgroundColor: '#f87979',
                            data: [this.getRandomInt(), this.getRandomInt()]
                        }
                    ]
                }
            },
            getRandomInt () {
                return Math.floor(Math.random() * (50 - 5 + 1)) + 5
            }
        },
        mounted() {
            this.fillData()
        }
    }
</script>
