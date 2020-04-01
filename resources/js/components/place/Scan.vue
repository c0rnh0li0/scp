<template>
    <v-container fluid>
        <v-row class="mx-2">
            <v-col cols="12" id="scanner-container">
                <v-card class="mx-auto" max-width="500" elevation="10" :loading="loading">
                    <v-card-title>QR Code Scanner</v-card-title>
                    <v-divider></v-divider>
                    <v-card-subtitle v-if="error" class="red--text font-weight-bolder">{{ error }}</v-card-subtitle>
                    <v-card-text>
                        <qrcode-stream @decode="onDecode" @init="onInit" />
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'

    export default {
        name: "Scan",
        components: {
            QrcodeStream,
            QrcodeDropZone,
            QrcodeCapture
        },
        data () {
            return {
                result: '',
                error: '',
                loading: false
            }
        },
        methods: {
            onDecode (result) {
                this.result = result
                console.log('scan result', this.result)
                let chunks = this.result.split('_')

                //$user, $offer, $amount
                let str = 'user: ' + chunks[0] + '\n'
                    + 'offer: ' + chunks[1] + '\n'
                    + 'amount: ' + chunks[2] + '\n'
                    + 'bought: ' + chunks[3] + '\n'

                alert(str)
                //1_4_2_2020-03-30 11:41:41
            },

            async onInit (promise) {
                console.log('loading qr code scanner')
                this.loading = true

                try {
                    await promise
                    this.loading = false
                } catch (error) {
                    /*if (error.name === 'NotAllowedError') {
                        this.error = "ERROR: you need to grant camera access permisson"
                    } else if (error.name === 'NotFoundError') {
                        this.error = "ERROR: no camera on this device"
                    } else if (error.name === 'NotSupportedError') {
                        this.error = "ERROR: secure context required (HTTPS, localhost)"
                    } else if (error.name === 'NotReadableError') {
                        this.error = "ERROR: is the camera already in use?"
                    } else if (error.name === 'OverconstrainedError') {
                        this.error = "ERROR: installed cameras are not suitable"
                    } else if (error.name === 'StreamApiNotSupportedError') {
                        this.error = "ERROR: Stream API is not supported in this browser"
                    }*/
                    this.error = error.name + ': ' + error.message
                    this.loading = false
                }
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
