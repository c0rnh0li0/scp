<template>
    <v-card>
        <v-card-title>
            <div class="font-weight-bold">{{ offer.title }}</div>
            <v-spacer></v-spacer>
            <v-btn icon dark @click="$emit('closeTicketDialog', offer)">
                <v-icon color="black">mdi-close</v-icon>
            </v-btn>
        </v-card-title>
        <v-divider></v-divider>
        <v-parallax v-if="offer.promo_image" :src="$store.state.promo_images_path + offer.promo_image" :lazy-src="$store.state.promo_images_path + offer.promo_image"
                    height="300"
                    class="white--text align-end"
                    gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)">
            <v-row align="end" class="justify-bottom">
                <v-col class="text-start" cols="12">
                    <h4 class="subheading" v-html="offer.short_description" />
                </v-col>
            </v-row>
        </v-parallax>
        <v-card-text>
            <v-container grid-list-xl>
                <v-layout row wrap>
                    <!-- long description -->
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <div v-html="offer.long_description" />
                    </v-flex>

                    <!-- pricing and checkboxes -->
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <v-row>
                            <v-col class="text-end" cols="5">
                                <v-label>Real price: </v-label><br />
                                <div class="d-inline real-price-text grey--text subheading">{{ offer.real_price }}</div>
                                <div class="d-inline grey--text subheading" v-if="offer.owner_details" v-html="offer.owner_details.valute.sign" />
                            </v-col>
                            <v-col cols="2" class="text-center">
                                <v-divider vertical />
                            </v-col>
                            <v-col cols="5" class="text-start">
                                <v-label>Offered price: </v-label><br />
                                <div class="d-inline offered-price-text green--text text--darken-4 font-weight-medium headline">{{ offer.offered_price }}</div>
                                <div class="d-inline font-weight-medium green--text text--darken-4 font-weight-medium headline" v-if="offer.owner_details" v-html="offer.owner_details.valute.sign" />
                            </v-col>
                        </v-row>
                    </v-flex>

                    <!-- notes -->
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <v-label>Notes about this offer:</v-label>
                        <div v-html="offer.notes" />
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-container grid-list-xl fluid>
                <v-layout row wrap>
                    <v-flex xs10 sm10 md10 lg5 xl5>
                        <v-row>
                            <v-col class="text-end" cols="12">
                                <v-select
                                        :items="tickets_amount"
                                        label="Tickets amount"
                                        :value="selected_ticket_amount"
                                        @change="ticketAmountChanged">
                                </v-select>
                            </v-col>
                        </v-row>
                    </v-flex>

                    <v-flex xs10 sm10 md10 lg5 xl5>
                        <v-row>
                            <v-col class="text-end" cols="12">
                                <v-text-field
                                        type="number"
                                        v-model="tickets_amount_custom"
                                        ref="customTicketsAmount"
                                        v-show="custom_ticket_amount_visible"
                                        label="Tickets amount"
                                        :rules="[integerRule]"
                                        hint="(Number)"/>
                            </v-col>
                        </v-row>
                    </v-flex>
                </v-layout>
            </v-container>

            <v-spacer />
            <v-btn color="success"
                   fab large
                   class="pl-6 pr-6 white--text font-weight-bold"
                   @click="confirmBuyTicket">
                <v-icon dark>mdi-check-bold</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "BuyTicket",
        props: ['offer', ],
        watch: {
            offer(newVal, oldVal) {
                console.log(newVal)
                return newVal
            },
            selected_ticket_amount(newVal, oldVal) {
                console.log('selected tickets amount', newVal)

                if (newVal == this.tickets_amount_custom_lbl) {
                    this.custom_ticket_amount_visible = true
                }
                else {
                    this.custom_ticket_amount_visible = false
                    this.total_tickets = newVal
                    this.tickets_amount_custom = newVal
                }
            },
            tickets_amount_custom(newVal, oldVal) {
                console.log('custom tickets amount', newVal)
                this.total_tickets = newVal
            }
        },
        data: () => ({
            tickets_amount: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 'More than 10...'],
            selected_ticket_amount: 1,
            tickets_amount_custom: 1,
            tickets_amount_custom_lbl: 'More than 10...',
            custom_ticket_amount_visible: false,
            total_tickets: 1,
            integerRule: v => {
                console.log('integer rule', v)
                if (parseInt(v) != NaN && Number.isInteger(v))
                    return true

                return 'Number has to be positive and not decimal'
            }
        }),
        methods: {
            confirmBuyTicket() {
                this.$emit('confirmBuyTicket', this.offer, this.total_tickets)
            },
            ticketAmountChanged(newVal) {
                console.log('selected tickets amount changed', newVal)
                this.selected_ticket_amount = newVal

                if (this.selected_ticket_amount == this.tickets_amount_custom_lbl) {
                    this.custom_ticket_amount_visible = true
                }
                else {
                    this.custom_ticket_amount_visible = false
                    this.total_tickets = this.selected_ticket_amount
                    this.tickets_amount_custom = this.selected_ticket_amount
                }
            }
        }
    }
</script>

<style scoped>
    .real-price-text {
        text-decoration: line-through red;
    }
</style>