<template>
    <v-card :color="boxColor" outlined elevation="1" class="d-flex">
        <v-card class="mr-auto" dark color="transparent" outlined flat>
            <v-card-subtitle class="pt-2 pb-0">
                <span class="subtitle-1 white--text">{{ todayText }}</span>
            </v-card-subtitle>
            <v-card-text class="pt-0 pb-0 mt-0">
                <span class="display-1 pt-0 mt-0 font-weight-bold white--text">
                    <animated-number :value="today" :delay="delay" :formatValue="formatInt" :duration="today_duration"></animated-number>
                </span>
            </v-card-text>

            <v-card-subtitle class="pt-4 pb-2">
                <div>
                    <span class="caption white--text">{{ weekText }}</span><br />
                    <span class="body-2 white--text">
                        <animated-number :value="thisWeek" :delay="delay" :formatValue="formatInt" :duration="week_duration"></animated-number>
                    </span>
                </div>
            </v-card-subtitle>
        </v-card>
        <v-card outlined flat dark color="transparent">
            <v-card-title class="pa-0 justify-end">
                <v-icon class="widget-icon text-end">{{ boxIcon }}</v-icon>
            </v-card-title>

            <v-card-subtitle class="pt-2 pb-0">
                <span>&nbsp;</span>
            </v-card-subtitle>

            <v-card-subtitle class="pt-1 pb-2">
                <div class="text-end justify-end">
                    <span class="caption white--text text-end">{{ monthText }}</span><br />
                    <span class="body-2 white--text text-end">
                        <animated-number :value="thisMonth" :delay="delay" :formatValue="formatInt" :duration="month_duration"></animated-number>
                    </span>
                </div>
            </v-card-subtitle>
        </v-card>
    </v-card>
</template>

<script>
    import AnimatedNumber from 'animated-number-vue'

    export default {
        name: "InfoCube",
        components: {
            AnimatedNumber
        },
        props: [
            'today',
            'todayText',
            'thisWeek',
            'weekText',
            'thisMonth',
            'monthText',
            'boxIcon',
            'boxColor'
        ],
        data: () => ({
            today_duration: 500,
            week_duration: 1000,
            month_duration: 1500,
            delay: 500
        }),
        methods: {
            formatInt(val) {
                return Number.parseInt(val)
            },
        },
    }
</script>

<style scoped>
    .widget-icon {
        font-size: 80px !important;
    }
</style>