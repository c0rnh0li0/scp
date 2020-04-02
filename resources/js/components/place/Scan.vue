<template>
    <v-container fluid>
        <v-row class="mx-2">
            <v-col cols="12" id="scanner-container">
                <v-card class="mx-auto" max-width="500" elevation="10" :loading="loading">
                    <v-card-title>QR Code Scanner</v-card-title>
                    <v-divider></v-divider>
                    <v-card-subtitle v-if="error" class="red--text font-weight-bolder">{{ error }}</v-card-subtitle>
                    <v-card-text>
                        <qrcode-stream :camera="camera" @decode="onDecode" @init="onInit">
                            <div v-if="validationSuccess" class="validation-success">
                                Ticket is valid
                            </div>

                            <div v-if="validationFailure" class="validation-failure">
                                Ticket is NOT valid!
                            </div>

                            <div v-if="validationPending" class="validation-pending">
                                Long validation in progress...
                            </div>
                        </qrcode-stream>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="ticket_confirm_dialog" persistent max-width="600" :fullscreen="$vuetify.breakpoint.mdAndDown">
            <ticket-check :ticket="ticket_check"
                          @closeTicketCheck="closeTicketCheck"
                          @confirmTicketCheck="confirmTicketCheck"
                          :validation_message="ticket_valid_message"
                          :ticket_validity="ticket_validity"
                          :button_disabled="confirm_button_disabled" />
        </v-dialog>
        <form-helpers :snackbar_visible="snackbar"
                      :snack_color="snack_color"
                      :snack_message="snack_message"
                      :message="progress_message"
                      :saving="saving"></form-helpers>
    </v-container>
</template>

<script>
    import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
    import TicketCheck from './custom/TicketCheck'
    import FormHelpers from '../custom/FormHelpers'

    export default {
        name: "Scan",
        components: {
            QrcodeStream,
            QrcodeDropZone,
            QrcodeCapture,
            FormHelpers,
            TicketCheck
        },
        computed: {
            validationPending () {
                return this.isValid === undefined
                    && this.camera === 'off'
            },

            validationSuccess () {
                return this.isValid === true
            },

            validationFailure () {
                return this.isValid === false
            }
        },
        watch: {
            ticket_check(newVal, oldVal) {
                console.log('ticket check watch', newVal)
                this.ticket_check = newVal
            },
        },
        data () {
            return {
                qr_regex: /(\d+)_(\d+)_(\d+)_([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))\s((0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9])/,
                result: '',
                error: '',
                loading: false,
                isValid: undefined,
                camera: 'auto',

                // form helpers stuff
                saving: false,
                snackbar: false,
                snack_message: '',
                snack_color: '',
                progress_message: '',

                ticket_confirm_dialog: false,
                ticket_check: null,
                ticket_valid_message: '',
                ticket_validity: '',
                confirm_button_disabled: false
            }
        },
        methods: {
            onDecode (result) {
                this.result = result
                //this.turnCameraOff()

                console.log('scan result', this.result)

                this.isValid = this.qr_regex.test(this.result)
                if (this.isValid) {
                    let chunks = this.result.split('_')

                    //1_4_2_2020-03-30 11:41:41
                    //ticket, user, amount, date
                    let str = 'ticket: ' + chunks[0] + '\n'
                        + 'user: ' + chunks[1] + '\n'
                        + 'amount: ' + chunks[2] + '\n'
                        + 'bought: ' + chunks[3] + '\n'

                    this.getTicketConfirmation(chunks[0], chunks[1], chunks[2], chunks[3])
                    console.log('valid qr code', str)
                }
                else {
                    console.log('invalid qr code', this.result)
                }
            },
            async onInit (promise) {
                this.loading = true

                try {
                    await promise
                    this.loading = false
                } catch (error) {
                    this.error = error.name + ': ' + error.message
                    this.loading = false
                }
            },
            turnCameraOn () {
                this.camera = 'auto'
            },
            turnCameraOff () {
                this.camera = 'off'
            },
            getTicketConfirmation(ticket, user, amount, date) {
                this.saving = true
                this.snackbar = false
                this.progress_message = 'Verifying ticket...'

                let formData = new FormData()
                formData.append('method', 'POST')
                formData.append('_method', 'POST')
                formData.append('ticket_id', ticket)
                formData.append('user_id', user)
                formData.append('amount', amount)
                formData.append('date', date)

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                let that = this
                axios.post('/api/tickets/check', formData, requestOptions)
                    .then(response => {
                        that.showTicketCheck(response.data)
                    })
                    .catch(error => {
                        that.errors = error.data.errors
                        that.progress_message = error.data.message
                    })
                    .then(() => {
                        that.saving = false
                        //that.snackbar = true
                    })
            },
            showTicketCheck(ticketData) {
                this.ticket_check = ticketData.ticket

                if (this.ticket_check.used == 1) {
                    this.ticket_valid_message = "Ticket has already been used"
                    this.ticket_validity = 'error--text'
                    this.confirm_button_disabled = true
                }
                else {
                    if (this.ticket_check.offer.owner.id == this.$store.state.session.user.id) {
                        this.ticket_valid_message = "Ticket is valid and ready to use"
                        this.ticket_validity = 'text-success'
                        this.confirm_button_disabled = false
                    }
                    else {
                        this.ticket_valid_message = "Ticket is for another location:\n" + this.ticket_check.offer.owner.name
                        this.ticket_validity = 'error--text'
                        this.confirm_button_disabled = true
                    }
                }

                this.ticket_confirm_dialog = true
            },
            closeTicketCheck() {
                this.ticket_confirm_dialog = false
            },
            confirmTicketCheck(ticket) {
                console.log('confirming ticket')

                this.confirm_button_disabled = true
                this.saving = true
                this.snackbar = false
                this.progress_message = 'Using ticket...'

                let formData = new FormData()
                formData.append('method', 'POST')
                formData.append('_method', 'POST')
                formData.append('ticket_id', ticket.id)

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                let that = this
                axios.post('/api/tickets/use', formData, requestOptions)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'
                        that.closeTicketCheck()
                    })
                    .catch(error => {
                        that.errors = error.data.errors
                        that.snack_message = error.data.message
                        that.snack_color = 'error'
                    })
                    .then(() => {
                        that.saving = false
                        that.snackbar = true
                        that.confirm_button_disabled = false
                    })
            }
        }
    }
</script>

<style scoped>
    .error {
        font-weight: bold;
        color: white;
        padding: 1rem;
    }
</style>
