<template>
    <div>
        <v-snackbar v-model="snackbar" :timeout="snack_timeout" :color="snack_color" top>
            {{ snack_message }}
            <v-icon v-if="snack_color == 'success'" color="white">mdi-check</v-icon>
            <v-icon v-if="snack_color == 'error'" color="white">mdi-alert</v-icon>
        </v-snackbar>
        <v-dialog v-model="saving" hide-overlay persistent width="300">
            <v-card color="primary" dark>
                <v-card-title>Saving</v-card-title>
                <v-card-text>
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "FormHelpers",
        props: ['snackbar_visible', 'snack_color', 'snack_message', 'saving'],
        watch: {
            snackbar_visible(newVal, oldVal) {
                this.snackbar = newVal
            },
        },
        data: () => ({
            snack_timeout: 2000,
            snackbar: false
        })
    }
</script>

<style scoped>

</style>