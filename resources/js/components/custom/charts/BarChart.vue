<script>
    import { Bar, mixins } from 'vue-chartjs'
    const { reactiveProp } = mixins

    export default {
        name: "BarChart",
        extends: Bar,
        mixins: [reactiveProp],
        props: ['chartData', 'options', 'type'],
        mounted () {
            this.renderChart(this.type, this.chartData, this.options)
        }
    }
</script>

<style scoped>

</style>