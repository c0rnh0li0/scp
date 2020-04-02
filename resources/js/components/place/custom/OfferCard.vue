<template>
    <v-hover v-slot:default="{ hover }">
        <v-card class="ma-2"
                :elevation="hover ? 12 : 2"
                :class="{ 'on-hover': hover }">
            <v-img :src="$store.state.promo_images_path + offer.promo_image"
                   class="white--text align-end"
                   gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                   height="200px">
                <v-card-title class="font-weight-bold" v-text="offer.title"></v-card-title>
            </v-img>
            <v-card-subtitle v-html="offer.short_description"></v-card-subtitle>
            <v-card-actions>
                <v-row class="text-start pl-2">
                    <v-col cols="12">
                        <div class="d-inline real-price-text grey--text caption">{{ offer.real_price }}</div>
                        <div class="d-inline grey--text" v-html="offer.owner_details.valute.sign" />

                        <br />

                        <div class="d-inline offered-price-text green--text text--darken-4 font-weight-medium title">{{ offer.offered_price }}</div>
                        <div class="d-inline font-weight-medium title green--text text--darken-4" v-html="offer.owner_details.valute.sign" />
                    </v-col>
                </v-row>
                <v-spacer></v-spacer>

                <v-btn icon @click="$emit('editOffer', offer)">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>

                <v-btn icon @click="$emit('askDeleteOffer', offer)">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-hover>
</template>

<script>
    export default {
        name: "OfferCard",
        props: ['offer'],
        watch: {
            offer(newVal, oldVal) {
                return newVal
            },
        },
        data: () => ({

        })
    }
</script>

<style scoped>
    .real-price-text {
        text-decoration: line-through red;
    }
</style>