<template>
    <div>
        <v-snackbar v-model="snackbar" :timeout="snack_timeout" :color="snack_color" top>
            <v-icon v-if="snack_color == 'success'" color="white">mdi-check</v-icon>
            <v-icon v-if="snack_color == 'error'" color="white">mdi-close-octagon</v-icon>
            <
            {{ snack_message }}
        </v-snackbar>
        <v-dialog v-model="saving" hide-overlay persistent width="300">
            <v-card color="primary" dark>
                <v-card-title>{{ dialog_title }}</v-card-title>
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
        props: ['snackbar_visible', 'snack_color', 'snack_message', 'saving', 'message'],
        watch: {
            snackbar_visible(newVal, oldVal) {
                this.snackbar = newVal
            },
            message(newVal, oldVal) {
                this.dialog_title = newVal
            }
        },
        data: () => ({
            snack_timeout: 2000,
            snackbar: false,
            dialog_title: 'Saving...'
        })
    }
</script>

<style scoped>

</style>