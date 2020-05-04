<script>
    import Chart from 'chart.js'

    import { Bar, mixins } from 'vue-chartjs'
    const { reactiveProp } = mixins

    export default {
        name: "HorizontalBarChart",
        extends: Bar,
        mixins: [reactiveProp],
        props: ['chartData', 'options', 'type'],
        mounted () {
            //var ctx = this.$refs.canvas.getContext('2d');

            /*window.horizontalBar = new Chart(ctx, {
                type: 'horizontalBar',
                data: this.chartData,
                options: this.options
            });*/

            this.renderChart(this.type, this.chartData, this.options)
        }
    }
</script>

<style scoped>

</style>