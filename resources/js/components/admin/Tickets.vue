<template>
    <div>
        <v-data-table
                :headers="headers"
                :items="items"
                :search="search"
                :options.sync="options"
                :server-items-length="totalItems"
                :loading="loading"
                sort-by="created_at"
                class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>Tickets</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Search"
                            single-line
                            hide-details
                            @change="searchChanged"
                    ></v-text-field>
                </v-toolbar>
            </template>
            <template v-slot:item.offer.owner.name="{ item }">
                <v-avatar size="25" class="mr-2">
                    <v-img v-if="item.offer.owner_details.picture" :src="$store.state.avatars_path + item.offer.owner_details.picture" width="25" height="25" aspect-ratio="1"></v-img>
                </v-avatar>
                {{ item.offer.owner.name }}
            </template>
            <template v-slot:item.user.name="{ item }">
                <v-avatar size="25" class="mr-2">
                    <v-img v-if="item.user_details.picture" :src="$store.state.avatars_path + item.user_details.picture" width="25" height="25" aspect-ratio="1"></v-img>
                </v-avatar>
                {{ item.user.name }}
                <span class="overline" v-if="item.user_details.location.country.code">
                    ({{ item.user_details.location.country.code }})
                </span>
            </template>
            <template v-slot:item.used="{ item }">
                <v-icon v-if="item.used == 1" color="success darken-2">mdi-check</v-icon>
            </template>
            <template v-slot:item.action="{ item }">
                <v-icon v-if="item.used == 0" @click="askUseTicket(item)">
                    mdi-qrcode-scan
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="getDataFromApi">Reset</v-btn>
            </template>
        </v-data-table>

        <v-dialog v-model="use_dialog" persistent max-width="290">
            <v-card>
                <v-card-title>Are you sure you want to use this ticket??</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-card-subtitle>If you use this ticket it will be displayed as USED when the <b>{{ useTicket_user_name }}</b> visits <b>{{ useTicket_owner_name }}</b>.</v-card-subtitle>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary darken-1" text @click="cancelUseTicket">No</v-btn>
                    <v-btn color="error darken-1" @click="confirmUseTicket">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <form-helpers :snackbar_visible="snackbar"
                      :snack_color="snack_color"
                      :snack_message="snack_message"
                      :saving="saving"></form-helpers>
    </div>
</template>

<script>
    import FormHelpers from '../custom/FormHelpers'

    export default {
        name: "Tickets",
        components: {
            FormHelpers
        },
        computed: {

        },
        watch: {
            dialog (val) {
                val || this.close()
            },
            options: {
                handler () {
                    this.getDataFromApi()
                        .then(data => {
                            this.items = data.items
                            this.totalItems = data.total
                        })
                },
                deep: true,
            },
            search(newVal, oldVal){
                this.getDataFromApi()
                    .then(data => {
                        this.items = data.items
                        this.totalItems = data.total
                    })
            },
        },
        data () {
            return {
                api: 'tickets',
                dialog: false,
                totalItems: 0,
                items: [],
                loading: true,
                options: {},
                headers: [
                    {text: 'Tourist', align: 'left', value: 'user.name', sortable: false },
                    {text: 'Place', value: 'offer.owner.name', sortable: false },
                    {text: 'Amount', value: 'amount', sortable: false },
                    {text: 'Bought on', value: 'created_at', sortable: false },
                    {text: 'Used', value: 'used', sortable: false },
                    {text: '', align: 'right', value: 'action', sortable: false},
                ],
                search: '',
                errors: [],

                // form helpers stuff
                saving: false,
                snackbar: false,
                snack_message: '',
                snack_color: '',

                progress: false,

                // deleting offers
                use_dialog: false,
                useTicket: null,
                useTicket_user_name: '',
                useTicket_owner_name: ''
            }
        },

        methods: {
            searchChanged(e) {
                if (this.search.length >= 2)
                    this.getDataFromApi()
                        .then(data => {
                            this.items = data.items
                            this.totalItems = data.total
                        })
            },
            getDataFromApi () {
                this.loading = true
                return new Promise((resolve, reject) => {
                    const { sortBy, sortDesc, page, itemsPerPage } = this.options

                    let queryParams = {
                        itemsPerPage: this.options.itemsPerPage,
                        page: this.options.page,
                        sortBy: this.options.sortBy[0],
                        dir: this.options.sortDesc.length > 0 ? 'DESC' : 'ASC',
                        q: this.search.length >= 2 ? this.search : ''
                    }

                    axios.get('/api/' + this.api, {
                        params: queryParams
                    }).then(data => {
                        this.loading = false
                        resolve({
                            items: data.data.data,
                            total: data.data.meta.total,
                        })
                    })
                })
            },
            askUseTicket(ticket) {
                this.useTicket = ticket
                this.useTicket_user_name = this.useTicket.user.name
                this.useTicket_owner_name = this.useTicket.offer.owner.name
                this.use_dialog = true
            },
            cancelUseTicket() {
                this.use_dialog = false
                this.useTicket = null
            },
            confirmUseTicket() {
                const index = this.items.indexOf(this.useTicket)

                this.saving = true
                this.snackbar = false

                let that = this
                axios.post('/api/tickets/use/', {
                    'ticket_id': this.useTicket.id
                }).then(response => {
                    that.snack_message = response.data.message
                    that.snack_color = response.data.success ? 'success' : 'error'

                    Object.assign(that.items[index], response.data.ticket)

                    that.useTicket = null
                    that.use_dialog = false
                })
                .catch(error => {
                    if (error.data.errors)
                        that.errors = error.data.errors

                    that.snack_message = error.data.message
                    that.snack_color = 'error'
                })
                .then(() => {
                    that.saving = false
                    that.snackbar = true
                })
            },
        },
    }
</script>

<style scoped>

</style>
