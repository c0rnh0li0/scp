<template>
    <info-cube :today="today"
               :today-text="$t('message.widgets.dailynewusers.today_text')"
               :this-week="this_week"
               :week-text="$t('message.widgets.dailynewusers.week_text')"
               :this-month="this_month"
               :month-text="$t('message.widgets.dailynewusers.month_text')"
               :box-icon="box_icon"
               :box-color="box_color">
    </info-cube>
</template>

<script>
    import InfoCube from '../custom/widgets/InfoCube'

    export default {
        name: "DailyNewUsers",
        components: {
            InfoCube
        },
        data: () => ({
            today: 0,
            this_week: 0,
            this_month: 0,
            today_text: 'New tourists today',
            week_text: 'This week',
            month_text: 'This month',
            box_icon: 'mdi-account-multiple-plus',
            box_color: 'blue darken-3'
        }),
        methods: {
            async getData() {
                let ticketsData = await axios.get('/api/dailytouristsdata')
                this.today = Number(ticketsData.data.chartdata.today)
                this.this_week = Number(ticketsData.data.chartdata.week)
                this.this_month = Number(ticketsData.data.chartdata.month)
            },
        },
        async mounted() {
            await this.getData()
        },
        created() {}
    }
</script>

<style scoped>

</style>