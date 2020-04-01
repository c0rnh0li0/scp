<template>
    <v-card>
        <v-card-title>
            <div class="font-weight-bold">Ticket for "{{ offer.title }}"</div>
            <v-spacer></v-spacer>
            <v-btn icon dark @click="$emit('closeQRTicketDialog', offer)">
                <v-icon color="black">mdi-close</v-icon>
            </v-btn>
        </v-card-title>
        <v-divider></v-divider>
        <v-parallax v-if="offer.promo_image" :src="$store.state.promo_images_path + offer.promo_image" :lazy-src="$store.state.promo_images_path + offer.promo_image"
                    height="200"
                    class="white--text align-end"
                    gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)">
            <v-row align="end" class="justify-bottom">
                <v-col class="text-start" cols="12">
                    <h4 class="subheading" v-html="offer.short_description" />
                </v-col>
            </v-row>
        </v-parallax>
        <v-card-text>
            <v-container grid-list-xl fluid fill-height>
                <v-layout row wrap>
                    <v-row class="justify-center text-center">
                        <v-col cols="12" class="text-center">
                            <h3>Congratulations!</h3>
                            <div class="caption">
                                Make sure you show your ticked on your way in
                            </div>
                        </v-col>
                        <v-col cols="12" class="text-center">
                            <img width="200" height="200" :src="$store.state.tickets_path + ticket.qr_code" />
                        </v-col>
                    </v-row>
                </v-layout>
            </v-container>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <v-btn color="success"
                   fab large
                   class="pl-6 pr-6 white--text font-weight-bold"
                   @click="$emit('closeQRTicketDialog', offer)">
                <v-icon dark>mdi-check-bold</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "QrTicketDialog",
        props: ['offer', 'ticket'],
        watch: {
            offer(newVal, oldVal) {
                console.log(newVal)
                return newVal
            },
            ticket(newVal, oldVal) {
                console.log('ticket', newVal)
                return newVal
            },
        },
        methods: {
        }
    }
</script>