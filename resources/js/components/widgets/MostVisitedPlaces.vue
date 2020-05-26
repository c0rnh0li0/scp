<template>
    <v-card outlined elevation="1" :loading="loading">
        <v-toolbar color="green darken-1" dark dense elevation="1">
            <v-icon>mdi-bank</v-icon>
            <v-card-title>
                {{ $t('message.widgets.mostvisitedplaces.title') }}
            </v-card-title>
        </v-toolbar>
        <v-card-text class="pa-2" style="overflow-y: auto;">
            <v-simple-table fixed-header height="100%">
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">{{ $t('message.widgets.mostvisitedplaces.header_place') }}</th>
                            <th class="text-end pr-2">{{ $t('message.widgets.mostvisitedplaces.header_visits') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in chartdata" :key="item.name">
                            <td>{{ item.name }}</td>
                            <td class="text-end pr-2">{{ item.visits }}</td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-card-subtitle>
                {{ $t('message.widgets.mostvisitedplaces.footer') }}
            </v-card-subtitle>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "MostVisitedPlaces",
        data: () => ({
            loading: true,
            chartdata: null
        }),
        methods: {
            async getChartData() {
                let ticketsData = await axios.get('/api/mostvisitedplaces')
                this.loading = false
                this.chartdata = ticketsData.data.chartdata
            },
        },
        async mounted () {
            this.loading = true
            await this.getChartData()
        }
    }
</script>

<style scoped>

</style>