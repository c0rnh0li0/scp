<template>
    <div>
        <v-card>
            <v-card-title>
                Contract info
                <v-spacer></v-spacer>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-layout row wrap class="pa-2">
                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-select
                                    prepend-icon="mdi-calendar"
                                    v-model="contract_length_id"
                                    value="contract_length_id"
                                    :items="contract_lengths"
                                    item-value="id"
                                    item-text="name"
                                    label="Contract length"
                                    :error-messages="errors.contract_length_id">
                            </v-select>
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-text-field label="Price" readonly v-model="price" prepend-inner-icon="mdi-cash"></v-text-field>
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-menu
                                    ref="start_date_menu"
                                    v-model="start_date_menu"
                                    :close-on-content-click="false"
                                    :return-value.sync="start_date"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                            v-model="formatted_start_date"
                                            label="Starts at"
                                            prepend-icon="mdi-calendar-month"
                                            readonly
                                            v-on="on"></v-text-field>
                                </template>
                                <v-date-picker v-model="start_date" no-title scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="start_date_menu = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="processStartDate(start_date)">OK</v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-text-field
                                    v-model="formatted_end_date"
                                    label="Expires at"
                                    prepend-icon="mdi-calendar-month"
                                    readonly></v-text-field>
                        </v-col>
                    </v-flex>
                </v-layout>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text color="success" @click="save">Pay &amp; Activate</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    import { format, parseISO, addMonths } from 'date-fns'

    import FormHelpers from '../custom/FormHelpers'
    import Errors from '../custom/ErrorContainer'

    export default {
        name: "Contract",
        components: {
            FormHelpers,
            Errors
        },
        computed: {},
        watch: {
            lookupData(newVal, oldVal) {
                this.setLookupData(newVal)
            },
            contract_length_id (newVal, oldVal) {
                let cl = this.contract_lengths.find(cnt => cnt.id == newVal)
                this.price = cl.price_label
                this.processEndDate()
            }
        },
        data () {
            return {
                errors: [],

                // form helpers stuff
                saving: false,
                snackbar: false,
                snack_message: '',
                snack_color: '',

                progress: false,

                contract_lengths: [],
                start_date: '',
                end_date: '',
                formatted_start_date: '',
                formatted_end_date: '',
                start_date_menu: false,

                // form data
                id: -1,
                owner_id: -1,
                owner: {},
                contract_length_id: -1,
                length: {},
                price: 0,
                valute_id: -1,
                valute: {},
                paid: 0,
                valid: 0,
                start_at: '',
                expires_at: '',

                editedIndex: -1,
                editedItem: {
                    id: -1,
                    owner_id: -1,
                    owner: {},
                    contract_length_id: -1,
                    length: {},
                    price: 0,
                    valute_id: -1,
                    valute: {},
                    paid: 0,
                    valid: 0,
                    start_at: '',
                    expires_at: '',
                },
                defaultItem: {
                    id: -1,
                    owner_id: -1,
                    owner: {},
                    contract_length_id: -1,
                    length: {},
                    price: 0,
                    valute_id: -1,
                    valute: {},
                    paid: 0,
                    valid: 0,
                    start_at: '',
                    expires_at: '',
                },
            }
        },

        methods: {
            setEditedItem(item) {
                this.editedIndex = this.items.indexOf(item)
                let defObj = Object.assign({}, this.defaultItem)
                this.editedItem = {...defObj, ...item }

                this.id = this.editedItem.id
                this.owner_id = this.$store.state.session.user.id
                this.contract_length_id = this.editedItem.contract_length_id
                this.price = this.editedItem.price
                this.valute_id = this.editedItem.valute_id
                this.paid = this.editedItem.paid
                this.valid = this.editedItem.valid
                this.start_date = this.start_at = this.editedItem.start_at
                this.end_date = this.expires_at = this.editedItem.expires_at
            },
            resetEditedItem() {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.setEditedItem(this.editedItem)
                this.editedIndex = -1
            },
            editItem (item) {
                this.setEditedItem(item)
                this.dialog = true
            },

            save () {
                this.saving = this.btn_save_disabled = true
                this.snackbar = false

                this.start_at = format(parseISO(this.start_date), 'yyyy-MM-dd HH:mm:ss')
                this.expires_at = format(parseISO(this.end_date), 'yyyy-MM-dd HH:mm:ss')

                this.editedItem = {
                    id: this.id,
                    owner_id: this.$store.state.session.user.id,
                    contract_length_id: this.contract_length_id,
                    price: this.price,
                    valute_id: this.valute_id,
                    //paid: this.paid,
                    //valid: this.valid,
                    paid: true,
                    valid: true,
                    start_at: this.start_at,
                    expires_at: this.expires_at,
                }

                this.editedItem.method = 'POST'

                let formData = new FormData()
                for (var property in this.editedItem)
                    formData.append(property, this.editedItem[property]);

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                formData.append('_method', 'POST')

                let that = this
                axios.post('/api/contracts/save', formData, requestOptions)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        if (response.data.success) {
                            that.$store.dispatch('getSession')
                                .then(response => {
                                    that.$router.push('/place/dashboard')
                                })
                                .catch(err => {
                                    console.log('error getting session after paying contract', err)
                                })
                        }
                    })
                    .catch(error => {
                        if (error.data.errors)
                            that.errors = error.data.errors

                        that.snack_message = error.data.message
                        that.snack_color = 'error'
                    })
                    .then(() => {
                        that.saving = that.btn_save_disabled = false
                        that.snackbar = true
                    })
            },
            getContractLengths () {
                let that = this
                axios.get('/api/contractlengths')
                    .then(response => {
                        that.contract_lengths = response.data.data
                    })
                    .catch(error => {
                        console.log('error fetching contract lengths', error)
                    })
            },
            processStartDate(start_date) {
                this.$refs.start_date_menu.save(this.start_date)
                this.start_date = new Date(this.start_date).toISOString()
                this.formatted_start_date = format(parseISO(this.start_date), 'EEEE, MMMM do yyyy')
                this.processEndDate()
            },
            processEndDate() {
                if (this.start_date == '' || this.contract_length_id <= 0)
                    return

                let months_length = this.contract_lengths.find(cl => cl.id == this.contract_length_id).duration
                let start_parsed = parseISO(this.start_date)

                this.end_date = addMonths(start_parsed, months_length).toISOString()
                this.formatted_end_date = format(parseISO(this.end_date), 'EEEE, MMMM do yyyy')
            },
            fixDates() {
                if (this.start_at != '')
                    this.start_date = new Date(this.start_at).toISOString()
                else
                    this.start_date = new Date().toISOString()

                this.formatted_start_date = format(parseISO(this.start_date), 'EEEE, MMMM do yyyy')

                this.processEndDate()
            }
        },
        mounted() {
            this.getContractLengths()
        },
        created() {
            this.fixDates()
        }
    }
</script>

<style scoped>

</style>