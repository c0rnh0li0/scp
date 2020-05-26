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

                    <v-toolbar-title>{{ $t('message.sections.offers.section_title') }}</v-toolbar-title>
                    <v-spacer></v-spacer>

                    <v-dialog v-model="dialog" max-width="1000px" scrollable :fullscreen="$vuetify.breakpoint.mdAndDown">
                        <template v-slot:activator="{ on }">
                            <v-btn color="success" dark class="mb-2" v-on="on">{{ $t('message.sections.offers.btn_new_title') }}</v-btn>
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
                                                :label="$t('message.sections.offers.form.fields.owner')">
                                            <template v-slot:no-data>
                                                <v-list-item>
                                                    <v-list-item-title>
                                                        {{ $t('message.sections.offers.form.fields.owner') }}
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
                                    </v-flex>

                                    <!-- offer title -->
                                    <v-flex xs12 sm12 md12 lg12 xl12>
                                        <v-input hidden v-model="id" />
                                        <v-input name="owner_id" hidden v-model="owner_id" />
                                        <v-text-field
                                                prepend-icon="mdi-tag"
                                                :label="$t('message.sections.offers.form.fields.title')"
                                                v-model="title"
                                                :error-messages="errors.title" />
                                    </v-flex>

                                    <!-- short description -->
                                    <v-flex xs12 sm12 md12 lg12 xl12 class="mt-2">
                                        <v-label ref="shortDescriptionLabel">{{ $t('message.sections.offers.form.fields.short_description') }}</v-label>
                                        <tiptap-vuetify
                                                v-model="short_description"
                                                :extensions="extensions"
                                                ref="shortDescription"
                                                :placeholder="$t('message.sections.offers.form.fields.short_description_placeholder')"
                                        />
                                        <errors :message="errors.short_description" />
                                    </v-flex>

                                    <!-- long description -->
                                    <v-flex xs12 sm12 md12 lg12 xl12 class="mt-2">
                                        <v-label>{{ $t('message.sections.offers.form.fields.long_description') }}</v-label>
                                        <tiptap-vuetify
                                                v-model="long_description"
                                                :extensions="extensions"
                                                :placeholder="$t('message.sections.offers.form.fields.long_description_placeholder')"
                                        />
                                    </v-flex>

                                    <!-- promo image -->
                                    <v-flex xs12 sm12 md6 lg6 xl6 class="text-center justify-space-between mt-5 pr-4">
                                        <v-label @click.stop="pickFile">{{ $t('message.sections.offers.form.fields.promo_image') }}</v-label>

                                        <input type="file"
                                               style="display: none;"
                                               name="promo_image"
                                               ref="image"
                                               accept="image/*"
                                               @change="onFilePicked">
                                        <v-spacer></v-spacer>
                                        <v-img :src="placeholderImage" height="250" v-if="placeholderImage" class="avatar-img" @click.stop="pickFile" aspect-ratio="1"></v-img>
                                    </v-flex>

                                    <!-- pricing and checkboxes -->
                                    <v-flex xs12 sm12 md6 lg6 xl6 class="text-center justify-space-between">
                                        <v-col cols="12" class="justify-space-between">
                                            <v-row>
                                                <v-col cols="11">
                                                    <v-text-field
                                                            prepend-icon="mdi-cash"
                                                            :label="$t('message.sections.offers.form.fields.real_price')"
                                                            v-model="real_price"
                                                            :error-messages="errors.real_price"
                                                    />
                                                </v-col>
                                                <v-col cols="1" class="d-flex align-middle align-center">
                                                    <div v-html='valute' />
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col cols="11">
                                                    <v-text-field
                                                            prepend-icon="mdi-cash-refund"
                                                            :label="$t('message.sections.offers.form.fields.offered_price')"
                                                            v-model="offered_price"
                                                            :error-messages="errors.offered_price"
                                                    />
                                                </v-col>
                                                <v-col cols="1" class="d-flex align-middle align-center">
                                                    <div v-html='valute' />
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                    <span v-on="on">
                                        <v-checkbox v-model="include_global" class="mx-2" :label="$t('message.sections.offers.form.fields.global_offer')"></v-checkbox>
                                    </span>
                                                    </template>
                                                    <span>{{ $t('message.sections.offers.form.fields.global_offer_hint') }}</span>
                                                </v-tooltip>
                                            </v-row>
                                            <v-row>
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                    <span v-on="on">
                                        <v-checkbox v-model="featured" class="mx-2" :label="$t('message.sections.offers.form.fields.featured')"></v-checkbox>
                                    </span>
                                                    </template>
                                                    <span>{{ $t('message.sections.offers.form.fields.featured_hint') }}</span>
                                                </v-tooltip>
                                            </v-row>
                                        </v-col>
                                    </v-flex>

                                    <!-- notes -->
                                    <v-flex xs12 sm12 md12 lg12 xl12 class="mt-2">
                                        <v-label>{{ $t('message.sections.offers.form.fields.notes') }}</v-label>
                                        <tiptap-vuetify
                                                v-model="notes"
                                                :extensions="extensions"
                                                :placeholder="$t('message.sections.offers.form.fields.notes_placeholder')"
                                        />
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
            </template>
            <template v-slot:item.offered_price="{ item }">
                {{ item.offered_price }} {{ item.owner_details.valute.sign }}
            </template>
            <template v-slot:item.action="{ item }">
                <v-icon
                        color="primary darken-3"
                        class="mr-4"
                        @click="editItem(item)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                        color="error darken-3"
                        @click="askDeleteOffer(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="getDataFromApi">{{ $t('message.global.lbl_reset') }}</v-btn>
            </template>
        </v-data-table>

        <v-dialog v-model="delete_dialog" persistent max-width="290">
            <v-card>
                <v-card-title>{{ $t('message.global.msg.delete_ask') }} "{{ deleteOffertitle }}"?</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-card-subtitle>"{{ deleteOffertitle }}" {{ $t('message.global.msg.delete_ask_msg') }}.</v-card-subtitle>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary darken-1" text @click="cancelOfferDelete">{{ $t('message.global.btn_no') }}</v-btn>
                    <v-btn color="error darken-1" @click="deleteOfferConfirmed">{{ $t('message.global.btn_yes') }}</v-btn>
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
    import {
        TiptapVuetify,
        Heading,
        Bold,
        Italic,
        Strike,
        Underline,
        Code,
        Paragraph,
        BulletList,
        OrderedList,
        ListItem,
        Link,
        Blockquote,
        HardBreak,
        HorizontalRule,
        History,
        Image,
        TodoList,
        TodoItem
    } from 'tiptap-vuetify'

    import FormHelpers from '../custom/FormHelpers'
    import originalPlaceholderImage from "../place/assets/placeholder-img.jpg"
    import Errors from '../custom/ErrorContainer'

    export default {
        name: "Offers",
        components: {
            TiptapVuetify,
            FormHelpers,
            Errors
        },
        computed: {
            formTitle () {
                //return this.editedIndex === -1 ? 'New offer' : 'Edit offer "' + this.editedItem.title + '"'
                return this.editedIndex === -1 ? this.$i18n.t('message.sections.offers.form.form_title_new') : this.$i18n.t('message.sections.offers.form.form_title_edit') + '"' + this.editedItem.title + '"'
            },
            headers() {
                return [
                    {text: this.$t('message.sections.offers.headers.title'), align: 'left', sortable: true, value: 'title'},
                    {text: this.$t('message.sections.offers.headers.offered_price'), value: 'offered_price', sortable: true},
                    {text: this.$t('message.sections.offers.headers.owner'), value: 'owner.name', sortable: false},
                    {text: this.$t('message.sections.offers.headers.created'), value: 'created_at', sortable: true},
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
                if (val.length < 3)
                    return

                this.places_loading = true

                // Lazily load input items
                let that = this
                axios.get('/api/place/find?q=' + val)
                    .then(response => {
                        that.places = response.data.data
                    })
                    .catch(error => {
                        console.log('error fetching places', error)
                    })
                    .finally(() => (that.places_loading = false))
            },
        },
        data () {
            return {
                api: 'offers',
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

                searchTimeout: null,

                // declare extensions you want to use
                extensions: [
                    History,
                    Blockquote,
                    Link,
                    Underline,
                    Strike,
                    Italic,
                    ListItem, // if you need to use a list (BulletList, OrderedList)
                    BulletList,
                    OrderedList,
                    Image,
                    [
                        Heading,
                        {
                            // Options that fall into the tiptap's extension
                            options: {
                                levels: [1, 2, 3, 4]
                            }
                        }
                    ],
                    Bold,
                    Link,
                    Code,
                    HorizontalRule,
                    TodoList,
                    [TodoItem, {
                        options: {
                            nested: true
                        }
                    }],
                    Paragraph,
                    HardBreak // line break on Shift + Ctrl + Enter
                ],

                // image upload component
                originalPlaceholderImage,
                placeholderImage: originalPlaceholderImage,
                images: [],
                activeImageUploads: {},

                // autocomplete
                places_loading: false,
                places: [],
                places_search: null,

                // form data
                id: 0,
                owner_id: 0,
                title: '',
                short_description: '',
                long_description: '',
                promo_image: '',
                real_price: '',
                offered_price: '',
                include_global: '',
                featured: '',
                notes: '',

                valute: '',

                // deleting offers
                delete_dialog: false,
                deleteOffer: null,
                deleteOffertitle: '',

                editedIndex: -1,
                editedItem: {
                    'id': -1,
                    'title': '',
                    'short_description': '',
                    'long_description': '',
                    'promo_image': '',
                    'owner_id': -1,
                    'real_price': '',
                    'offered_price': '',
                    'include_global': false,
                    'featured': false,
                    'notes': '',
                    'modified_by': '',
                    'deleted_by': '',
                    'created_at': '',
                    'updated_at': '',
                    'deleted_at': ''
                },
                defaultItem: {
                    'id': -1,
                    'title': '',
                    'short_description': '',
                    'long_description': '',
                    'promo_image': '',
                    'owner_id': -1,
                    'real_price': '',
                    'offered_price': '',
                    'include_global': false,
                    'featured': false,
                    'notes': '',
                    'modified_by': '',
                    'deleted_by': '',
                    'created_at': '',
                    'updated_at': '',
                    'deleted_at': ''
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
            setEditedItem(item) {
                this.editedIndex = this.items.indexOf(item)
                let defObj = Object.assign({}, this.defaultItem)
                this.editedItem = {...defObj, ...item }

                this.id = this.editedItem.id
                this.owner_id = this.editedItem.owner_id
                this.title = this.editedItem.title
                this.short_description = this.editedItem.short_description
                this.long_description = this.editedItem.long_description
                this.promo_image = this.editedItem.promo_image
                this.placeholderImage = this.editedItem.promo_image ? this.$store.state.promo_images_path + this.editedItem.promo_image : this.originalPlaceholderImage
                this.real_price = this.editedItem.real_price
                this.offered_price = this.editedItem.offered_price
                this.include_global = this.editedItem.include_global
                this.featured = this.editedItem.featured
                this.notes = this.editedItem.notes
            },
            resetEditedItem() {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.setEditedItem(this.editedItem)
                this.editedIndex = -1
                this.images = []
            },
            editItem (item) {
                this.setEditedItem(item)
                this.dialog = true
            },

            askDeleteOffer(offer) {
                this.deleteOffer = offer
                this.deleteOffertitle = offer.title
                this.delete_dialog = true
            },
            cancelOfferDelete() {
                this.delete_dialog = false
                this.deleteOffer = null
                this.deleteOffertitle = ''
            },
            deleteOfferConfirmed() {
                this.deleteItem(this.deleteOffer)
            },
            deleteItem (item) {
                const index = this.items.indexOf(item)

                this.saving = true
                this.snackbar = false

                let that = this
                axios.post('/api/offers/delete/' + this.deleteOffer.id)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        that.items.splice(index, 1)

                        that.deleteOffer = null
                        that.deleteOffertitle = ''
                        that.delete_dialog = false
                    })
                    .catch(error => {
                        that.errors = error.data.errors
                        that.snack_message = error.data.message
                        that.snack_color = 'error'
                    })
                    .then(() => {
                        that.saving = false
                        that.snackbar = true
                    })

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

                this.editedObject = {
                    id: this.id,
                    owner_id: this.owner_id,
                    title: this.title,
                    short_description: this.short_description,
                    long_description: this.long_description,
                    promo_image: this.promo_image,
                    real_price: this.real_price,
                    offered_price: this.offered_price,
                    include_global: this.include_global,
                    featured: this.featured,
                    notes: this.notes
                }

                this.editedObject.method = 'POST'

                let formData = new FormData()

                for (var property in this.editedObject) {
                    if (property == 'promo_image' && this.images.length == 1)
                        formData.append(property, this.images[0], this.images[0].name);
                    else
                        formData.append(property, this.editedObject[property]);
                }

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                formData.append('_method', 'POST')

                let that = this
                axios.post('/api/offers/save/' + this.editedObject.id, formData, requestOptions)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        if (that.editedIndex > -1)
                            Object.assign(that.items[that.editedIndex], response.data.offer)
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
            pickFile () {
                this.$refs.image.click();
            },
            onFilePicked (e) {
                const files = e.target.files
                if(files[0] !== undefined) {
                    const fr = new FileReader ()
                    fr.readAsDataURL(files[0])
                    fr.addEventListener('load', () => {
                        this.placeholderImage = fr.result
                        this.images.push(files[0]) // this is an image file that can be sent to server...
                    });
                } else {
                    this.placeholderImage = ''
                    this.images = []
                }
            },
        },
    }
</script>

<style scoped>

</style>
