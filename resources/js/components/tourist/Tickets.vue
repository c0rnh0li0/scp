<template>
    <v-container fluid class="fill-height ma-0 pa-0">
        <v-row class="mx-2">
            <v-col cols="12" align="center" justify="center" v-if="tickets_loaded && tickets.length == 0">
                <v-alert type="info" align="center" justify="center" max-width="400">
                    No tickets at this time...
                </v-alert>
            </v-col>
            <v-col cols="12" align="center" justify="center" class="ma-0 pa-0" v-else>
                <v-card flat class="ma-0 pa-0 mb-2">
                    <v-card-title class="display-1">
                        Your unused tickets
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-xl class="ma-0 pa-0">
                            <v-layout row wrap class="ma-0 pa-0">
                                <v-flex v-for="(ticket, i) in unused_tickets" :key="ticket.id" xs12 sm6 md4 lg3 xl3 class="ma-0 pa-0">
                                    <ticket-card :ticket="ticket" @openTicket="openTicket" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>

                <!-- <v-card flat class="ma-0 pa-0 mb-2" v-if="used_tickets.length > 0">
                    <v-card-title class="display-1">
                        Used tickets
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-xl class="ma-0 pa-0">
                            <v-layout row wrap class="ma-0 pa-0">
                                <v-flex v-for="(ticket, i) in used_tickets" :key="ticket.id" xs12 sm6 md4 lg3 xl3 class="ma-0 pa-0">
                                    <ticket-card :ticket="ticket" @openTicket="openTicket" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card> -->
            </v-col>
        </v-row>
        <v-dialog v-model="ticket_dialog" max-width="1000" :fullscreen="$vuetify.breakpoint.mdAndDown">
            <ticket-details :ticket="preview_ticket" @closeTicket="closeTicket" />
        </v-dialog>

    </v-container>
</template>

<script>
    import TicketCard from './custom/TicketCard'
    import TicketDetails from './custom/TicketDetails'

    export default {
        components: {
            TicketCard,
            TicketDetails
        },
        watch: {
            tickets(newVal, oldVal) {
                return newVal
            },
        },
        data: () => ({
            tickets: [],
            unused_tickets: [],
            used_tickets: [],
            preview_ticket: null,
            ticket_dialog: false,
            tickets_loaded: false
        }),
        methods: {
            getTickets() {
                let that = this
                axios.get('/api/tickets/')
                    .then(response => {
                        that.tickets = response.data.data
                        that.unused_tickets = that.tickets
                    })
                    .catch(error => {
                        console.log('error fetching tickets')
                    })
                    .then(() => {
                        that.tickets_loaded = true
                    })
            },
            openTicket(ticket) {
                this.preview_ticket = ticket
                this.ticket_dialog = true
            },
            closeTicket(offer) {
                this.ticket_dialog = false
            },
        },
        created() {
            console.log('Tickets Component created.')
            this.getTickets()
        }
    }
</script>