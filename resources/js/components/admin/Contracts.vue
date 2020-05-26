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
                <v-toolbar flat extended color="white">
                    <template v-slot:extension>
                        <v-spacer v-if="$vuetify.breakpoint.mdAndUp"></v-spacer>
                        <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                :label="$t('message.global.lbl_search')"
                                single-line
                                hide-details
                                @change="searchChanged"
                        ></v-text-field>
                        <v-spacer v-if="$vuetify.breakpoint.mdAndUp"></v-spacer>
                    </template>

                    <v-toolbar-title>{{ $t('message.sections.contracts.section_title') }}</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="1000px" scrollable :fullscreen="$vuetify.breakpoint.mdAndDown">
                        <template v-slot:activator="{ on }">
                            <v-btn color="success" dark class="mb-2" v-on="on">{{ $t('message.sections.contracts.btn_new_title') }}</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                {{ formTitle }}
                                <v-spacer></v-spacer>
                                <v-btn icon dark @click="dialog = !dialog">
                                    <v-icon color="black">mdi-close</v-icon>
                                </v-btn>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-layout row wrap class="pa-2">
                                    <v-flex xs12 sm12 md12 lg12 xl12>
                                        <v-col cols="12">
                                            <v-autocomplete
                                                    v-model="owner_id"
                                                    :loading="places_loading"
                                                    :items="places"
                                                    item-text="user.name"
                                                    item-value="user.id"
                                                    :search-input.sync="places_search"
                                                    prepend-icon="mdi-bank"
                                                    hide-no-data
                                                    hide-details
                                                    :label="$t('message.sections.contracts.form.fields.owner')">
                                                <template v-slot:no-data>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            {{ $t('message.sections.contracts.form.fields.owner') }}
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </template>
                                                <template v-slot:item="{ item }">
                                                    <v-list-item-avatar color="indigo" class="headline font-weight-light white--text">
                                                        {{ item.user.name.charAt(0) }}
                                                    </v-list-item-avatar>
                                                    <v-list-item-content>
                                                        <v-list-item-title v-text="item.user.name"></v-list-item-title>
                                                        <v-list-item-subtitle v-text="item.location.category.name + ' / ' + item.location.subcategory.name"></v-list-item-subtitle>
                                                    </v-list-item-content>
                                                </template>
                                            </v-autocomplete>
                                        </v-col>
                                    </v-flex>

                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-select
                                                    prepend-icon="mdi-calendar"
                                                    v-model="contract_length_id"
                                                    value="contract_length_id"
                                                    :items="contract_lengths"
                                                    item-value="id"
                                                    item-text="name"
                                                    :label="$t('message.sections.contracts.form.fields.length')"
                                                    :error-messages="errors.contract_length_id">
                                            </v-select>
                                        </v-col>
                                    </v-flex>

                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-text-field :label="$t('message.sections.contracts.form.fields.price')" readonly v-model="price" prepend-inner-icon="mdi-cash"></v-text-field>
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
                                                            :label="$t('message.sections.contracts.form.fields.starts_at')"
                                                            prepend-icon="mdi-calendar-month"
                                                            readonly
                                                            v-on="on"></v-text-field>
                                                </template>
                                                <v-date-picker v-model="start_date" no-title scrollable>
                                                    <v-spacer></v-spacer>
                                                    <v-btn text color="primary" @click="start_date_menu = false">{{ $t('message.global.btn_close') }}</v-btn>
                                                    <v-btn text color="primary" @click="processStartDate(start_date)">{{ $t('message.global.btn_ok') }}</v-btn>
                                                </v-date-picker>
                                            </v-menu>
                                        </v-col>
                                    </v-flex>

                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="formatted_end_date"
                                                :label="$t('message.sections.contracts.form.fields.expires_at')"
                                                prepend-icon="mdi-calendar-month"
                                                readonly></v-text-field>
                                        </v-col>
                                    </v-flex>

                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-checkbox v-model="paid" class="mx-2" :label="$t('message.sections.contracts.form.fields.paid')"></v-checkbox>
                                        </v-col>
                                    </v-flex>

                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-checkbox v-model="valid" class="mx-2" :label="$t('message.sections.contracts.form.fields.valid')"></v-checkbox>
                                        </v-col>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-btn text color="primary" @click="dialog = !dialog">{{ $t('message.global.btn_close') }}</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn text color="success" @click="save">{{ $t('message.global.btn_save') }}</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
                <v-divider></v-divider>
                <v-expansion-panels flat v-model="filters">
                    <v-expansion-panel>
                        <v-expansion-panel-header>{{ $t('message.global.lbl_filters') }}</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-layout row wrap class="pa-2">
                                <v-flex xs6 sm6 md4 lg2 xl2>
                                    <v-col cols="12">
                                        <v-select
                                                :items="paid_filter_items"
                                                :label="$t('message.sections.contracts.filters.lbl_paid')"
                                                item-text="text"
                                                item-value="value"
                                                v-model="filter_paid"
                                        ></v-select>
                                    </v-col>
                                </v-flex>

                                <v-flex xs6 sm6 md4 lg2 xl2>
                                    <v-col cols="12">
                                        <v-select
                                                :items="valid_filter_items"
                                                :label="$t('message.sections.contracts.filters.lbl_valid')"
                                                item-text="text"
                                                item-value="value"
                                                v-model="filter_valid"
                                        ></v-select>
                                    </v-col>
                                </v-flex>

                                <v-flex xs6 sm6 md4 lg2 xl2>
                                    <v-col cols="12">
                                        <v-select
                                                :items="expired_filter_items"
                                                :label="$t('message.sections.contracts.filters.lbl_expired')"
                                                item-text="text"
                                                item-value="value"
                                                v-model="filter_expired"
                                        ></v-select>
                                    </v-col>
                                </v-flex>
                            </v-layout>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
                <v-divider></v-divider>
            </template>
            <!-- <template v-slot:item.owner.name="{ item }">
                <span color="text--error darken-3" v-if="item.valid != 1">{{ item.owner.name }}</span>
                <span v-else>{{ item.owner.name }}</span>
            </template> -->
            <template v-slot:item.paid="{ item }">
                <v-icon v-if="item.paid == 1" color="success darken-1">mdi-check</v-icon>
                <v-icon v-else color="error darken-1">mdi-close</v-icon>
            </template>
            <template v-slot:item.valid="{ item }">
                <v-icon v-if="item.valid == 1" color="success darken-1">mdi-check</v-icon>
                <v-icon v-else color="error darken-1">mdi-close</v-icon>
            </template>
            <template v-slot:item.length.duration="{ item }">
                {{ item.length.name }}
            </template>
            <template v-slot:item.length.price="{ item }">
                {{ item.length.price }}{{ item.length.valute.sign }}
            </template>
            <template v-slot:item.action="{ item }">
                <v-icon
                        color="primary darken-3"
                        class="mr-4"
                        @click="editItem(item)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon disabled color="error darken-3">
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="getDataFromApi">{{ $t('message.global.lbl_reset') }}</v-btn>
            </template>
        </v-data-table>

        <form-helpers :snackbar_visible="snackbar"
                      :snack_color="snack_color"
                      :snack_message="snack_message"
                      :saving="saving"></form-helpers>
    </div>
</template>

<script>
    import { format, parseISO, addMonths } from 'date-fns'

    import FormHelpers from '../custom/FormHelpers'
    import Errors from '../custom/ErrorContainer'

    export default {
        name: "Contracts",
        components: {
            FormHelpers,
            Errors
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? this.$i18n.t('message.sections.contracts.form.form_title_new') : this.$i18n.t('message.sections.contracts.form.form_title_edit')
            },
            headers() {
                return [
                    {text: this.$t('message.sections.contracts.headers.owner'), value: 'owner.name', sortable: false},
                    {text: this.$t('message.sections.contracts.headers.paid'), value: 'paid', align: 'center', sortable: false},
                    {text: this.$t('message.sections.contracts.headers.valid'), value: 'valid', align: 'center', sortable: false},
                    {text: this.$t('message.sections.contracts.headers.duration'), value: 'length.duration', sortable: false},
                    {text: this.$t('message.sections.contracts.headers.price'), value: 'length.price', align: 'center', sortable: false},
                    {text: this.$t('message.sections.contracts.headers.created'), value: 'created_at', sortable: false},
                    {text: this.$t('message.sections.contracts.headers.starts_at'), value: 'start_at_list', align: 'left', sortable: false},
                    {text: this.$t('message.sections.contracts.headers.expires_at'), value: 'expires_at_list', sortable: false},
                    {text: this.$t('message.sections.contracts.headers.reminders'), value: 'reminders', align: 'center', sortable: false},
                    {text: '', align: 'right', value: 'action', sortable: false},
                ]
            },
        },
        watch: {
            dialog (val) {
                val || this.close()
            },
            lookupData(newVal, oldVal) {
                this.setLookupData(newVal)
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
            places_search (val) {
                if (val.length < 3 && this.all_places.length == 0)
                    return

                if (this.all_places.length == 0)
                    this.getPlaces(val)
            },
            contract_length_id (newVal, oldVal) {
                let cl = this.contract_lengths.find(cnt => cnt.id == newVal)
                this.price = cl.price
                this.processEndDate()
            },
            filter_paid (newVal, oldVal) {
                if (this.filters > -1)
                    this.filters = -1

                this.getDataFromApi()
                    .then(data => {
                        this.items = data.items
                        this.totalItems = data.total
                    })
            },
            filter_valid (newVal, oldVal) {
                if (this.filters > -1)
                    this.filters = -1

                this.getDataFromApi()
                    .then(data => {
                        this.items = data.items
                        this.totalItems = data.total
                    })
            },
            filter_expired (newVal, oldVal) {
                if (this.filters > -1)
                    this.filters = -1

                this.getDataFromApi()
                    .then(data => {
                        this.items = data.items
                        this.totalItems = data.total
                    })
            }
        },
        data: function() {
            return {
                api: 'contracts',
                dialog: false,
                totalItems: 0,
                items: [],
                loading: true,
                options: {},

                search: '',
                errors: [],

                // form helpers stuff
                saving: false,
                snackbar: false,
                snack_message: '',
                snack_color: '',

                progress: false,

                // autocomplete
                places_loading: false,
                all_places: [],
                places: [],
                places_search: null,

                contract_lengths: [],
                start_date: '',
                end_date: '',
                formatted_start_date: '',
                formatted_end_date: '',
                start_date_menu: false,

                // filters
                filters: false,
                paid_filter_items: [
                    { value: '', text: this.$i18n.t('message.sections.contracts.filters.paid.all') },
                    { value: 1, text: this.$i18n.t('message.sections.contracts.filters.paid.paid') },
                    { value: 0, text: this.$i18n.t('message.sections.contracts.filters.paid.unpaid') },
                ],
                filter_paid: '',
                valid_filter_items: [
                    { value: '', text: this.$i18n.t('message.sections.contracts.filters.valid.all') },
                    { value: 1, text: this.$i18n.t('message.sections.contracts.filters.valid.valid') },
                    { value: 0, text: this.$i18n.t('message.sections.contracts.filters.valid.invalid') },
                ],
                filter_valid: '',
                expired_filter_items: [
                    { value: '', text: this.$i18n.t('message.sections.contracts.filters.expired.all') },
                    { value: 1, text: this.$i18n.t('message.sections.contracts.filters.expired.valid') },
                    { value: 0, text: this.$i18n.t('message.sections.contracts.filters.expired.expired') },
                ],
                filter_expired: '',

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
                        q: this.search.length >= 2 ? this.search : '',
                        paid: this.filter_paid,
                        valid: this.filter_valid,
                        expired: this.filter_expired,
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
            setEditedItem(item) {
                this.editedIndex = this.items.indexOf(item)
                let defObj = Object.assign({}, this.defaultItem)
                this.editedItem = {...defObj, ...item }

                this.id = this.editedItem.id
                this.owner_id = this.editedItem.owner_id
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

            close () {
                this.dialog = false
                setTimeout(() => {
                    this.resetEditedItem()
                }, 300)
            },

            save () {
                this.saving = this.btn_save_disabled = true
                this.snackbar = false

                this.start_at = format(parseISO(this.start_date), 'yyyy-MM-dd HH:mm:ss')
                this.expires_at = format(parseISO(this.end_date), 'yyyy-MM-dd HH:mm:ss')

                this.editedItem = {
                    id: this.id,
                    owner_id: this.owner_id,
                    contract_length_id: this.contract_length_id,
                    price: this.price,
                    valute_id: this.valute_id,
                    paid: this.paid,
                    valid: this.valid,
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

                        if (that.editedIndex > -1)
                            Object.assign(that.items[that.editedIndex], response.data.item)
                        else
                            this.items.push(response.data.offer)

                        that.close()
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
            getPlaces (val) {
                this.places_loading = true

                // Lazily load input items
                let that = this
                axios.get('/api/place/find?q=' + val)
                    .then(response => {
                        that.all_places = response.data.data
                        that.places = that.all_places
                    })
                    .catch(error => {
                        console.log('error fetching places', error)
                    })
                    .finally(() => (that.places_loading = false))
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
            this.getPlaces('')
        },
        created() {
            this.fixDates()
        }
    }
</script>

<style scoped>

</style>
