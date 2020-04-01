<template>
    <v-hover v-slot:default="{ hover }">
        <v-card class="ma-2"
                :elevation="hover ? 12 : 2"
                @click="$emit('showLocationDirections', ticket)"
                :class="{ 'on-hover': hover }">
            <v-img v-if="ticket.offer.promo_image" :src="$store.state.promo_images_path + ticket.offer.promo_image" :lazy-src="$store.state.promo_images_path + ticket.offer.promo_image"
                        height="200"
                        class="white--text align-end"
                        gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)">
                <v-card-title>{{ ticket.offer.title }}</v-card-title>
                <v-card-subtitle class="white--text text-start" v-html="ticket.offer.short_description" />
            </v-img>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-xl>
                    <v-layout row wrap>
                        <v-row class="justify-center text-center">
                            <v-col cols="12" class="text-center">
                                <img width="100" height="100" :src="$store.state.tickets_path + ticket.qr_code" />
                            </v-col>
                        </v-row>
                    </v-layout>
                </v-container>
            </v-card-text>
        </v-card>
    </v-hover>
</template>

<script>
    export default {
        name: "TicketCard",
        props: ['ticket'],
        watch: {
            ticket(newVal, oldVal) {
                console.log('preview ticket', newVal)
                return newVal
            },
        },
    }
</script>

<style scoped>

</style>