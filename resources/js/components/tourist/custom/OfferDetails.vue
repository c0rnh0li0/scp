<template>
    <v-card>
        <v-card-title>
            <div class="font-weight-bold">{{ offer.title }}</div>
            <v-spacer></v-spacer>
            <v-btn icon dark @click="$emit('closeOffer', offer)">
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
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <span class="body-2">From: </span>
                        <router-link class="body-1" :to="'/home/business/' + offer.owner.id + '/false'">{{ offer.owner.name }}</router-link>
                    </v-flex>

                    <v-flex xs12 sm12 md12 lg12 xl12>
                        {{ $t('message.sections.place.sections.offers.expires_at') }}:
                        <span class="font-weight-bold">{{ formatted_ends_at }}</span>
                    </v-flex>

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
        <v-card-actions v-if="hide_actions == false">
            <v-spacer />
            <v-btn color="success"
                   class="pl-6 pr-6 white--text font-weight-bold"
                   @click="$emit('buyTicket', offer)">
                Buy ticket
                <v-icon right dark>mdi-basket</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    import { format, parseISO } from 'date-fns'
    export default {
        name: "OfferDetails",
        props: ['offer', 'hide_actions'],
        watch: {
            offer(newVal, oldVal) {
                console.log(newVal)
                return newVal
            },
            hide_actions(newVal, oldVal) {
                return newVal
            },
        },
        data: () => ({
            formatted_ends_at: ''
        }),
        mounted() {
            if (this.offer.ends_at)
                this.formatted_ends_at = format(parseISO(this.offer.ends_at), 'EEEE, MMMM do yyyy')
        }
    }
</script>

<style scoped>
    .real-price-text {
        text-decoration: line-through red;
    }
</style>