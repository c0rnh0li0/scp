<template>
    <div>
        <v-layout row wrap class="pa-2">
            <v-row dense class="mx-auto">
                <v-flex xs12 sm16 md4 lg3 xl3>
                    <v-col cols="12">
                        <today-tickets :business-id="session.user.id"></today-tickets>
                    </v-col>
                </v-flex>
                <v-flex xs12 sm16 md4 lg3 xl3>
                    <v-col cols="12">
                        <today-places :business-id="session.user.id"></today-places>
                    </v-col>
                </v-flex>

                <v-flex xs12 sm12 md6 lg6 xl6>
                    <v-col cols="12">
                        <monthly-tickets :business-id="session.user.id"></monthly-tickets>
                    </v-col>
                </v-flex>
            </v-row>
            <v-row dense class="mx-auto">
                <v-flex xs12 sm12 md6 lg6 xl6>
                    <v-col cols="12">
                        <yearly-visitors :business-id="session.user.id"></yearly-visitors>
                    </v-col>
                </v-flex>

                <v-flex xs12 sm12 md6 lg6 xl6>
                    <v-col cols="12">
                        <yearly-tickets :business-id="session.user.id"></yearly-tickets>
                    </v-col>
                </v-flex>
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
    import { mapState } from 'vuex'

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
        computed:
            mapState({
                session: state => state.session,
            })
        ,
        data: () => ({

        }),
        methods: {},
        mounted() {}
    }
</script>