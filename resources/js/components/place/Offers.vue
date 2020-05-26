<template>
    <div>
        <v-col cols="12" v-if="offers_loaded && offers.length == 0">
            <v-alert type="info" max-width="400">
                No offers at this time...
            </v-alert>
        </v-col>
        <v-col cols="12" v-else>
            <div row class="display-1 mb-2">{{ $t('message.sections.place.sections.offers.section_title') }}</div>

            <v-layout row wrap class="">
                <v-flex v-for="(offer, i) in offers" :key="offer.id" xs12 sm6 md4 lg3 xl3 class="">
                    <offer-card :offer="offer" @editOffer="editOffer" @askDeleteOffer="askDeleteOffer" />
                </v-flex>
            </v-layout>
        </v-col>

        <v-btn bottom
               color="success"
               dark
               fab
               fixed
               right
               @click="showForm">
            <v-icon>mdi-plus</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" scrollable persistent max-width="1000" :fullscreen="$vuetify.breakpoint.mdAndDown">
            <v-card>
                <v-card-title class="font-weight-bold">
                    {{ formTitle }}
                    <v-spacer></v-spacer>
                    <v-btn icon dark @click="closeForm">
                        <v-icon color="black">mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-xl fluid fill-height>
                        <v-layout row wrap>
                            <!-- offer title -->
                            <v-flex xs12 sm12 md12 lg12 xl12>
                                <v-input hidden v-model="id" />
                                <v-text-field
                                        prepend-icon="mdi-tag"
                                        :label="$t('message.sections.place.sections.offers.form.fields.title')"
                                        v-model="title"
                                        :error-messages="errors.title" />
                            </v-flex>

                            <!-- short description -->
                            <v-flex xs12 sm12 md12 lg12 xl12>
                                <v-label ref="shortDescriptionLabel">{{ $t('message.sections.place.sections.offers.form.fields.short_description') }}</v-label>
                                <tiptap-vuetify
                                        v-model="short_description"
                                        :extensions="extensions"
                                        ref="shortDescription"
                                        :placeholder="$t('message.sections.place.sections.offers.form.fields.short_description_placeholder')"
                                />
                                <errors :message="errors.short_description" />
                            </v-flex>

                            <!-- long description -->
                            <v-flex xs12 sm12 md12 lg12 xl12>
                                <v-label>{{ $t('message.sections.place.sections.offers.form.fields.long_description') }}</v-label>
                                <tiptap-vuetify
                                        v-model="long_description"
                                        :extensions="extensions"
                                        placeholder="$t('message.sections.place.sections.offers.form.fields.long_description_placeholder')"
                                />
                            </v-flex>

                            <!-- promo image -->
                            <v-flex xs12 sm12 md6 lg6 xl6 class="text-center justify-space-between">
                                <v-label @click.stop="pickFile">{{ $t('message.sections.place.sections.offers.form.fields.promo_image') }}</v-label>

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
                                                    :label="$t('message.sections.place.sections.offers.form.fields.real_price')"
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
                                                    :label="$t('message.sections.place.sections.offers.form.fields.offered_price')"
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
                                        <v-checkbox v-model="include_global" class="mx-2" :label="$t('message.sections.place.sections.offers.form.fields.global_offer')"></v-checkbox>
                                    </span>
                                            </template>
                                            <span>{{ $t('message.sections.place.sections.offers.form.fields.global_offer_hint') }}</span>
                                        </v-tooltip>
                                    </v-row>
                                    <v-row>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                    <span v-on="on">
                                        <v-checkbox v-model="featured" class="mx-2" :label="$t('message.sections.place.sections.offers.form.fields.featured')"></v-checkbox>
                                    </span>
                                            </template>
                                            <span>{{ $t('message.sections.place.sections.offers.form.fields.featured_hint') }}</span>
                                        </v-tooltip>
                                    </v-row>
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
                                                    :label="$t('message.sections.place.sections.offers.form.fields.starts_at')"
                                                    prepend-icon="mdi-calendar-month"
                                                    readonly
                                                    :error-messages="errors.starts_at"
                                                    v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="start_date" :min="new Date().toISOString().slice(0,10)" no-title scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="start_date_menu = false">{{ $t('message.global.btn_close') }}</v-btn>
                                            <v-btn text color="primary" @click="processStartDate(start_date)">{{ $t('message.global.btn_ok') }}</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-flex>

                            <v-flex xs12 sm12 md6 lg6 xl6>
                                <v-col cols="12">
                                    <v-menu
                                            ref="end_date_menu"
                                            v-model="end_date_menu"
                                            :close-on-content-click="false"
                                            :return-value.sync="end_date"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                    v-model="formatted_end_date"
                                                    :label="$t('message.sections.place.sections.offers.form.fields.ends_at')"
                                                    prepend-icon="mdi-calendar-month"
                                                    readonly
                                                    :error-messages="errors.ends_at"
                                                    v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="end_date" :min="endDateMin" :max="endDateMax" no-title scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="end_date_menu = false">{{ $t('message.global.btn_close') }}</v-btn>
                                            <v-btn text color="primary" @click="processEndDate(end_date)">{{ $t('message.global.btn_ok') }}</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-flex>

                            <!-- notes -->
                            <v-flex xs12 sm12 md12 lg12 xl12>
                                <v-label>{{ $t('message.sections.place.sections.offers.form.fields.notes') }}</v-label>
                                <tiptap-vuetify
                                        v-model="notes"
                                        :extensions="extensions"
                                        :placeholder="$t('message.sections.place.sections.offers.form.fields.notes_placeholder')"
                                />
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text color="error" @click="closeForm" :disabled="btn_save_disabled">{{ $t('message.global.btn_cancel') }}</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="saveForm" :disabled="btn_save_disabled">{{ $t('message.global.btn_save') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="preview_offer" :fullscreen="$vuetify.breakpoint.mdAndDown">
            <preview-offer :offer="preview_offer" :hide_actions="true" @closeOffer="closeOfferPreview" />
        </v-dialog>
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
                      :saving="saving" />
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
    import Errors from '../custom/ErrorContainer'
    import originalPlaceholderImage from "./assets/placeholder-img.jpg"
    import PreviewOffer from '../tourist/custom/OfferDetails'
    import OfferCard from './custom/OfferCard'
    import { format, parseISO, differenceInMonths, isAfter, addDays, addMonths } from 'date-fns'

    export default {
        components: {
            TiptapVuetify,
            FormHelpers,
            Errors,
            PreviewOffer,
            OfferCard
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? this.$t('message.sections.place.sections.offers.form.form_title_new') : this.$t('message.sections.place.sections.offers.form.form_title_edit') + ' "' + this.editedItem.title + '"'
            },
        },
        watch: {
            offers(newVal, oldVal) {
                return newVal
            },
        },
        data: () => ({
            // form helpers stuff
            saving: false,
            snackbar: false,
            snack_message: '',
            snack_color: '',

            btn_save_disabled: false,

            dialog: false,
            dialog_title: '',
            delete_dialog: false,
            preview_offer_dialog: false,
            preview_offer: null,
            drawer: null,
            errors: [],
            offers: [],
            offers_loaded: false,

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
            images: [],
            placeholderImage: originalPlaceholderImage,

            start_date: '',
            formatted_start_date: '',
            start_date_menu: false,

            end_date: '',
            formatted_end_date: '',
            end_date_menu: false,
            endDateMin: '',
            endDateMax: '',

            // form data
            id: 0,
            title: '',
            short_description: '',
            long_description: '',
            promo_image: '',
            real_price: '',
            offered_price: '',
            include_global: '',
            featured: '',
            notes: '',
            starts_at: '',
            ends_at: '',

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
                'starts_at': '',
                'ends_at': '',
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
                'starts_at': '',
                'ends_at': '',
                'modified_by': '',
                'deleted_by': '',
                'created_at': '',
                'updated_at': '',
                'deleted_at': ''
            },

            deleteOffer: null,
            deleteOffertitle: '',
            valute: ''
        }),
        methods: {
            getOffers() {
                let that = this
                axios.get('/api/offers/list')
                    .then(response => {
                        that.offers = response.data.data
                        that.getValuteHint()
                    })
                    .catch(error => {
                        console.log('error fetching offers')
                    })
                    .then(() => {
                        that.offers_loaded = true
                    })
            },
            getValuteHint() {
                this.valute = '[please select a currency from your profile settings]'
                if (this.$store.state.session.valute)
                    this.valute = this.$store.state.session.valute.sign
            },
            setEditedItem(item) {
                this.editedIndex = this.offers.indexOf(item)
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

                this.starts_at = this.start_date = this.editedItem.starts_at
                this.ends_at = this.end_date = this.editedItem.ends_at
            },
            resetEditedItem() {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.setEditedItem(this.editedItem)
                this.editedIndex = -1
                this.images = []
            },

            showForm() {
                this.errors = []
                this.resetEditedItem()
                this.dialog = true
            },
            editOffer(offer) {
                this.setEditedItem(offer)

                this.errors = []
                this.editedItem = offer

                this.fixDates()

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
                const index = this.offers.indexOf(this.deleteOffer)
                this.saving = true
                this.snackbar = false

                let that = this
                axios.post('/api/offers/delete/' + this.deleteOffer.id)
                    .then(response => {
                        that.getOffers()

                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        that.offers.splice(index, 1)

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

            previewOffer(offer) {
                this.preview_offer = offer
                this.preview_offer_dialog = true
            },
            closeOfferPreview() {
                this.preview_offer_dialog = false
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
            closeForm() {
                this.dialog = false
                this.resetFormData()
            },
            saveForm() {
                if (!this.checkDates())
                    return

                this.saving = this.btn_save_disabled = true
                this.snackbar = false

                this.fillEditedObject()

                let stringified = JSON.stringify(this.editedObject)
                this.editedObject.method = 'POST'
                this.editedObject.stringData = stringified

                let formData = new FormData()
                formData.append('jsonstring', this.editedObject.stringData)

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
                        that.getOffers()
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'
                        that.resetFormData()
                        that.dialog = false
                    })
                    .catch(error => {
                        that.errors = error.data.errors
                        that.snack_message = error.data.message
                        that.snack_color = 'error'
                    })
                    .then(() => {
                        that.saving = that.btn_save_disabled = false
                        that.snackbar = true
                    })
            },
            fillEditedObject() {
                console.log('start_date', this.start_date)
                console.log('end_date', this.end_date)
                console.log('-----------------------------')

                this.starts_at = format(parseISO(this.start_date), 'yyyy-MM-dd HH:mm:ss')
                this.ends_at = format(parseISO(this.end_date), 'yyyy-MM-dd HH:mm:ss')

                console.log(this.start_date)
                console.log(this.end_date)

                this.editedObject = {
                    id: this.id,
                    owner_id: this.$store.state.session.user.id,
                    title: this.title,
                    short_description: this.short_description,
                    long_description: this.long_description,
                    promo_image: this.promo_image,
                    real_price: this.real_price,
                    offered_price: this.offered_price,
                    include_global: this.include_global,
                    featured: this.featured,
                    notes: this.notes,
                    starts_at: this.starts_at,
                    ends_at: this.ends_at
                }
            },
            checkDates() {
                let hasErrors = false
                this.errors = {}

                if (!this.start_date || this.start_date == '') {
                    hasErrors = true
                    this.errors.starts_at = 'Please select a start date for your offer'
                }

                if (!this.end_date || this.end_date == '') {
                    hasErrors = true
                    this.errors.ends_at = 'Please select an end date for your offer'
                }

                if (!hasErrors) {
                    let s = new Date(this.start_date)
                    let e = new Date(this.end_date)

                    let m_dif = differenceInMonths(e, s)
                    console.log('is greater', isAfter(e, s))
                    console.log('m_dif', m_dif)

                    if (!isAfter(e, s)) {
                        hasErrors = true
                        this.errors.ends_at = 'Offer\'s end date must be greater than the start date'
                    }

                    if (m_dif > 3) {
                        hasErrors = true
                        this.errors.ends_at = 'Offer\'s end date must be not greater than three months from the start date'
                    }
                }

                if (hasErrors) {
                    this.snackbar = true
                    this.snack_color = 'error'
                    this.snack_message = 'You have to select correct dates for your offer'
                }

                return !hasErrors
            },
            resetFormData() {
                this.id = 0
                this.title = ''
                this.short_description = ''
                this.long_description = ''
                this.promo_image = ''
                this.owner = ''
                this.real_price = ''
                this.offered_price = ''
                this.include_global = ''
                this.featured = ''
                this.notes = ''
                this.starts_at = ''
                this.ends_at = ''
                this.start_date = ''
                this.end_date = ''
                this.formatted_start_date = ''
                this.formatted_end_date = ''
                this.endDateMin = ''
                this.endDateMax = ''

                this.images = []
                this.placeholderImage = originalPlaceholderImage

                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedObject = Object.assign({}, this.defaultItem)
                this.setEditedItem(this.editedItem)
                this.editedIndex = -1
                this.images = []
            },
            processStartDate(start_date) {
                if (!start_date || start_date == '')
                    return

                if (this.$refs.start_date_menu)
                    this.$refs.start_date_menu.save(start_date)

                this.start_date = new Date(start_date).toISOString()
                this.formatted_start_date = format(parseISO(this.start_date), 'EEEE, MMMM do yyyy')

                this.endDateMin = addDays(parseISO(this.start_date), 1).toISOString().slice(0,10)
                this.endDateMax = addMonths(parseISO(this.start_date), 3).toISOString().slice(0,10)
            },
            processEndDate(end_date) {
                if (!end_date || end_date == '')
                    return

                if (this.$refs.end_date_menu)
                    this.$refs.end_date_menu.save(end_date)
                
                this.end_date = new Date(end_date).toISOString()
                this.formatted_end_date = format(parseISO(this.end_date), 'EEEE, MMMM do yyyy')
            },
            fixDates() {
                this.processStartDate(this.starts_at)
                this.processEndDate(this.ends_at)
            }
        },
        mounted() {
            this.getOffers()
        },
        created() {}
    }
</script>

<style scoped>
    .real-price-text {
        text-decoration: line-through red;
    }
</style>
