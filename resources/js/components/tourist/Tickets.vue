<template>
    <div>
        <v-col cols="12" v-if="tickets_loaded && tickets.length == 0">
            <v-alert type="info" align="center" justify="center" max-width="400">
                No tickets at this time...
            </v-alert>
        </v-col>
        <v-col cols="12" v-else>
            <div row class="display-1 mb-2">Your unused tickets</div>
            <v-layout row wrap>
                <v-flex v-for="(ticket, i) in unused_tickets" :key="'ticket_' + ticket.id" xs12 sm6 md4 lg3 xl3>
                    <ticket-card :ticket="ticket" @openTicket="openTicket" />
                </v-flex>
            </v-layout>
        </v-col>

        <v-dialog v-model="ticket_dialog" max-width="1000" :fullscreen="$vuetify.breakpoint.mdAndDown">
            <ticket-details :ticket="preview_ticket" @closeTicket="closeTicket" />
        </v-dialog>
    </div>
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
                axios.get('/api/tickets/list')
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