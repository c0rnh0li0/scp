<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">QR code scanner</div>

                    <div class="card-body">
                        <p class="error">{{ error }}</p>
                        <qrcode-stream @decode="onDecode" @init="onInit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                error: ''
            }
        },
        methods: {
            onDecode (result) {
                this.result = result
            },

            async onInit (promise) {
                try {
                    await promise
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