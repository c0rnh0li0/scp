<template>
    <v-container fluid class="fill-height">
        <v-row class="mx-2">
            <v-col cols="12" align="center" justify="center" v-if="offers.length == 0">
                <v-alert type="info" align="center" justify="center" max-width="400">
                    No offers at this time...
                </v-alert>
            </v-col>
            <v-col cols="12" align="center" justify="center" v-else>
                <v-card flat class="mb-2">
                    <v-card-title class="display-1">
                        Featured offers
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-xl fluid fill-height>
                            <v-layout row wrap>
                                <v-flex v-for="(offer, i) in featured_offers" :key="offer.id" xs12 sm6 md4 lg3 xl3>
                                    <offer-card :offer="offer" @openOffer="openOffer" @buyTicket="buyTicket" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>

                <v-card flat class="mb-2">
                    <v-card-title class="display-1">
                        Other offers
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-xl fluid fill-height>
                            <v-layout row wrap>
                                <v-flex v-for="(offer, i) in other_offers" :key="offer.id" xs12 sm6 md4 lg3 xl3>
                                    <offer-card :offer="offer" @openOffer="openOffer" @buyTicket="buyTicket" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="view_offer_dialog" persistent scrollable max-width="1000">
            <offer-details :offer="viewOffer" :hide_actions="false" @closeOffer="closeOffer" @buyTicket="buyTicket" />
        </v-dialog>
        <v-dialog v-model="buy_ticket_dialog" persistent scrollable max-width="600">
            <ticket-dialog :offer="buyOfferTicket" @closeTicketDialog="closeTicketDialog" @confirmBuyTicket="confirmBuyTicket" :tickets_amoutn="buy_ticket_amount" />
        </v-dialog>

        <v-dialog v-model="qr_ticket_dialog" scrollable max-width="600">
            <qr-ticket-dialog :offer="qrOfferTicket" :ticket="previewTicket" @closeQRTicketDialog="closeQRTicketDialog" />
        </v-dialog>

        <form-helpers :snackbar_visible="snackbar"
                      :snack_color="snack_color"
                      :snack_message="snack_message"
                      :dialog_title="form_helper_saving_message"
                      :saving="saving"></form-helpers>
    </v-container>
</template>

<script>
    import FormHelpers from '../custom/FormHelpers'
    import OfferCard from './custom/OfferCard'
    import OfferDetails from './custom/OfferDetails'
    import TicketDialog from './custom/BuyTicket'
    import QrTicketDialog from './custom/QrTicketDialog'

    export default {
        components: {
            FormHelpers,
            OfferCard,
            OfferDetails,
            TicketDialog,
            QrTicketDialog
        },
        watch: {
            offers(newVal, oldVal) {
                return newVal
            },
        },
        data: () => ({
            // form helpers stuff
            snackbar: false,
            snack_message: '',
            snack_color: '',
            form_helper_saving_message: '',
            saving: false,

            offers: [],
            featured_offers: [],
            other_offers: [],
            viewOffer: null,
            view_offer_dialog: false,
            buyOfferTicket: null,
            buy_ticket_dialog: false,
            buy_ticket_amount: 1,
            qr_ticket_dialog: false,
            qrOfferTicket: null,
            previewTicket: null
        }),
        methods: {
            getOffers() {
                let that = this
                axios.get('/api/offers/get')
                    .then(response => {
                        that.offers = response.data.data
                        that.featured_offers = that.offers.filter(o => o.featured == 1)
                        that.other_offers = that.offers.filter(o => o.featured == 0)

                        that.getValuteHint()
                    })
                    .catch(error => {
                        console.log('error fetching offers')
                    })
                    .then(() => {

                    })
            },
            getValuteHint() {
                this.valute = '[please select a currency from your profile settings]'
                if (this.$store.state.session.valute)
                    this.valute = this.$store.state.session.valute.sign
            },
            openOffer(offer) {
                this.viewOffer = offer
                this.view_offer_dialog = true
            },
            closeOffer(offer) {
                this.view_offer_dialog = false
                //this.viewOffer = null
            },
            buyTicketConfirm() {

            },
            buyTicket(offer) {
                this.buyOfferTicket = offer
                this.buy_ticket_dialog = true
            },
            closeTicketDialog(offer) {
                this.buy_ticket_dialog = false
            },
            async confirmBuyTicket(offer, amount) {
                console.log('buy ticket confirm', offer)

                this.form_helper_saving_message = 'Buying ticket...'
                this.saving = true
                this.snackbar = false

                let formData = new FormData()
                formData.append('offer_id', offer.id)
                formData.append('owner_id', offer.owner_details.user.id)
                formData.append('amount', amount)
                formData.append('method', 'POST')
                formData.append('_method', 'POST')

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                let that = this
                axios.post('/api/tickets/buy', formData, requestOptions)
                    .then(async response => {
                        console.log('ticket bought: ', response.data)

                        that.qrOfferTicket = offer
                        that.qr_ticket_dialog = true
                        that.buy_ticket_dialog = false
                        that.previewTicket = response.data.ticket

                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'
                    })
                    .catch(error => {
                        console.log('error buying')
                        that.errors = error.data.errors
                        that.snack_message = error.data.message
                        that.snack_color = 'error'
                    })
                    .then(() => {
                        that.form_helper_saving_message = ''
                        that.saving = that.btn_save_disabled = false
                        that.snackbar = true
                    })
            },
            async generateQR(user, offer, ticket, amount) {
                return await axios.get('/api/tickets/qr/' + user + '/' + offer + '/' + ticket + '/' + amount)
            },
            closeQRTicketDialog() {
                this.qr_ticket_dialog = false
            }
        },
        mounted() {
            console.log('tourist offers Component mounted.')
            this.getOffers()
        }
    }
</script>

<style scoped>
    .real-price-text {
        text-decoration: line-through red;
    }
</style>
