<template>
    <v-card>
        <v-card-title class="font-weight-bold">
            Change password
            <v-spacer></v-spacer>
            <v-btn icon dark @click="$emit('closePasswordDialog')">
                <v-icon color="black">mdi-close</v-icon>
            </v-btn>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-container grid-list-xl>
                <v-layout row wrap>
                    <!-- Current password -->
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <v-text-field
                                type="password"
                                prepend-icon="mdi-lock-question"
                                label="Password"
                                v-model="password"
                                :error-messages="errors.password"
                        />
                    </v-flex>

                    <!-- New password -->
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <v-text-field
                                type="password"
                                prepend-icon="mdi-lock-question"
                                label="New Password"
                                v-model="new_password"
                                :error-messages="errors.new_password"
                        />
                    </v-flex>

                    <!-- Confirm new password -->
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <v-text-field
                                type="password"
                                prepend-icon="mdi-lock-question"
                                label="Confirm Password"
                                v-model="confirm_password"
                                :error-messages="errors.confirm_password"
                        />
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn large text @click="$emit('closePasswordDialog')">Close</v-btn>
            <v-spacer></v-spacer>
            <v-btn large color="success" :disabled="btn_save_disabled" @click="savePassword">Save</v-btn>
        </v-card-actions>
        <form-helpers :snackbar_visible="snackbar"
                      :snack_color="snack_color"
                      :snack_message="snack_message"
                      :saving="saving"></form-helpers>
    </v-card>
</template>

<script>
    import FormHelpers from '../../custom/FormHelpers'

    export default {
        name: "PasswordForm",
        components: {
            FormHelpers
        },
        data: () => ({
            password: '',
            new_password: '',
            confirm_password: '',
            password_data: {},

            btn_save_disabled: false,
            loading: false,

            errors: [],

            // form helpers stuff
            saving: false,
            snackbar: false,
            snack_message: '',
            snack_color: '',

            progress: false,
        }),
        methods: {
            savePassword() {
                this.saving = this.btn_save_disabled = true
                this.snackbar = false

                this.password_data.password = this.password
                this.password_data.new_password = this.new_password
                this.password_data.confirm_password = this.confirm_password

                let formData = new FormData()
                formData.append('method', 'POST')
                formData.append('_method', 'POST')
                formData.append('password', this.password_data.password)
                formData.append('new_password', this.password_data.new_password)
                formData.append('confirm_password', this.password_data.confirm_password)

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                let that = this
                axios.post('/api/password', formData, requestOptions)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'
                        this.$store.dispatch('getSession')
                    })
                    .catch(error => {
                        that.errors = error.data.errors
                        that.snack_message = error.data.message
                        that.snack_color = 'error'
                    })
                    .then(() => {
                        that.saving = that.btn_save_disabled = false
                        that.snackbar = true
                    })
            }
        }
    }
</script>

<style scoped>

</style>