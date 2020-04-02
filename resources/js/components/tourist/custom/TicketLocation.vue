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
            </v-img>
            <v-divider></v-divider>
            <v-card-text>
                <v-card-subtitle class="text-start" v-html="ticket.offer.short_description" />
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <img width="30" height="30" :src="$store.state.tickets_path + ticket.qr_code" />
            </v-card-actions>
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