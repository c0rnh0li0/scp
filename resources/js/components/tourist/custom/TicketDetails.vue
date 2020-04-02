<template>
    <v-card>
        <v-card-title>
            <div class="font-weight-bold">{{ ticket.offer.title }}</div>
            <v-spacer></v-spacer>
            <v-btn icon dark @click="$emit('closeTicket', ticket)">
                <v-icon color="black">mdi-close</v-icon>
            </v-btn>
        </v-card-title>
        <v-divider />
        <v-card-text class="text-center justify-center">
            <img width="400" height="400" :src="$store.state.tickets_path + ticket.qr_code" />
        </v-card-text>

        <v-img v-if="ticket.offer.promo_image" :src="$store.state.promo_images_path + ticket.offer.promo_image" :lazy-src="$store.state.promo_images_path + ticket.offer.promo_image"
               height="200"
               class="white--text align-end"
               gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)">
            <v-card-title v-html="ticket.offer.short_description" />
        </v-img>
        <v-card-text>
            <v-container grid-list-xl>
                <v-layout row wrap>
                    <!-- long description -->
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <div v-html="ticket.offer.long_description" />
                    </v-flex>

                    <!-- notes -->
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <v-label>Notes:</v-label>
                        <div v-html="ticket.offer.notes" />
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <v-btn class="ma-2"
                   color="success"
                   @click="$emit('closeTicket', ticket)">OK
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "TicketDetails",
        props: ['ticket'],
        watch: {
            ticket(newVal, oldVal) {
                return newVal
            },
        },
    }
</script>

<style scoped>

</style>