<template>
    <v-card>
        <v-card-title>
            <div class="font-weight-bold">Ticket validation</div>
            <v-spacer></v-spacer>
            <v-btn icon dark @click="$emit('closeTicketCheck', ticket)">
                <v-icon color="black">mdi-close</v-icon>
            </v-btn>
        </v-card-title>
        <v-divider />
        <v-img v-if="ticket.offer.promo_image" :src="$store.state.promo_images_path + ticket.offer.promo_image" :lazy-src="$store.state.promo_images_path + ticket.offer.promo_image"
               height="200"
               class="white--text align-end"
               gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)">
            <v-card-title v-html="ticket.offer.title" />
        </v-img>
        <v-card-text>
            <v-container grid-list-xl>
                <v-layout row wrap>
                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <div :class="'title text-center font-weight-bold ' + ticket_validity">
                            {{ validation_message }}
                        </div>
                    </v-flex>

                    <v-flex xs12 sm12 md12 lg12 xl12>
                        <v-layout row wrap>
                            <v-col cols="4">
                                <v-avatar size="80">
                                    <v-img :src="$store.state.avatars_path + ticket.user_details.picture" width="100" height="100" aspect-ratio="1"></v-img>
                                </v-avatar>
                            </v-col>
                            <v-col cols="8" class="justify-center center pt-4">
                                <div>Name: <b>{{ ticket.user.name }}</b></div>
                                <div>Email: <i>{{ ticket.user.email }}</i></div>
                                <div>People: <i>{{ ticket.amount }}</i></div>
                            </v-col>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>
        <v-divider />
        <v-card-actions>
            <v-spacer />
            <span class="title">
                Use ticket
            </span>
            <v-btn class="ma-2 font-weight-bold"
                   color="success"
                   large
                   fab
                   :disabled="button_disabled"
                   @click="$emit('confirmTicketCheck', ticket)">
                <v-icon light>mdi-check-bold</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "TicketCheck",
        props: ['ticket', 'validation_message', 'ticket_validity', 'button_disabled'],
        watch: {
            button_disabled(newVal, oldVal) {
                this.button_disabled = newVal
            },
        },
    }
</script>

<style scoped>

</style>