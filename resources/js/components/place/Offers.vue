<template>
    <v-container fluid class="fill-height">
        <v-row class="mx-2">
            <v-col cols="12" align="center" justify="center" v-if="offers.length == 0">
                <v-alert type="info" align="center" justify="center" max-width="400">
                    You don't have any offers for now
                </v-alert>
            </v-col>
            <v-col cols="12" align="center" justify="center" v-else>
                <v-container grid-list-xl fluid fill-height>
                    <v-layout row wrap>
                        <v-flex v-for="(offer, i) in offers" :key="offer.id" xs12 sm6 md4 lg3 xl3>
                            <v-card class="ma-2">
                                <v-img :src="$store.state.promo_images_path + offer.promo_image"
                                       class="white--text align-end"
                                       gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                       height="200px">
                                    <v-card-title class="font-weight-bold" v-text="offer.title"></v-card-title>
                                </v-img>
                                <v-card-subtitle v-html="offer.short_description"></v-card-subtitle>
                                <v-card-actions>
                                    <v-row class="text-start">
                                        <v-col cols="12">
                                            <div class="d-inline real-price-text grey--text caption">{{ offer.real_price }}</div>
                                            <div class="d-inline grey--text" v-html="$store.state.session.valute.sign" />

                                            <br />

                                            <div class="d-inline offered-price-text green--text text--darken-4 font-weight-medium title">{{ offer.offered_price }}</div>
                                            <div class="d-inline font-weight-medium title green--text text--darken-4" v-html="$store.state.session.valute.sign" />
                                        </v-col>
                                    </v-row>
                                    <v-spacer></v-spacer>

                                    <!-- <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <span v-on="on">
                                                <v-btn icon @click="previewOffer(offer)">
                                                    <v-icon>mdi-eye</v-icon>
                                                </v-btn>
                                            </span>
                                        </template>
                                        <span>Preview offer</span>
                                    </v-tooltip> -->

                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <span v-on="on">
                                                <v-btn icon @click="editOffer(offer)">
                                                    <v-icon>mdi-pencil</v-icon>
                                                </v-btn>
                                            </span>
                                        </template>
                                        <span>Edit</span>
                                    </v-tooltip>

                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <span v-on="on">
                                                <v-btn icon @click="askDeleteOffer(offer)">
                                                    <v-icon>mdi-delete</v-icon>
                                                </v-btn>
                                            </span>
                                        </template>
                                        <span>Delete</span>
                                    </v-tooltip>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-col>
        </v-row>
        <v-btn bottom
               color="success"
               dark
               fab
               fixed
               right
               @click="showForm">
            <v-icon>mdi-plus</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" scrollable persistent max-width="1000">
            <v-card>
                <v-card-title class="font-weight-bold">
                    {{ dialog_title }}
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
                                        label="Offer Title"
                                        v-model="title"
                                        :error-messages="errors.title" />
                            </v-flex>

                            <!-- short description -->
                            <v-flex xs12 sm12 md12 lg12 xl12>
                                <v-label ref="shortDescriptionLabel">Short Description</v-label>
                                <tiptap-vuetify
                                        v-model="short_description"
                                        :extensions="extensions"
                                        ref="shortDescription"
                                        placeholder="Add a short description for your offer…"
                                />
                                <errors :message="errors.short_description" />
                            </v-flex>

                            <!-- long description -->
                            <v-flex xs12 sm12 md12 lg12 xl12>
                                <v-label>Long Description</v-label>
                                <tiptap-vuetify
                                        v-model="long_description"
                                        :extensions="extensions"
                                        placeholder="Add a nice and long description for your offer…"
                                />
                            </v-flex>

                            <!-- promo image -->
                            <v-flex xs12 sm12 md6 lg6 xl6 class="text-center justify-space-between">
                                <v-label @click.stop="pickFile">Promo image</v-label>

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
                                                    label="Real Price"
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
                                                    label="Offered Price"
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
                                            <v-checkbox v-model="include_global" class="mx-2" label="Include in global offers?"></v-checkbox>
                                        </span>
                                            </template>
                                            <span>Can this offer be displayed publicly for tourists and can it be used in the creation of bundles (multiple offers) by our system?</span>
                                        </v-tooltip>
                                    </v-row>
                                    <v-row>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                        <span v-on="on">
                                            <v-checkbox v-model="featured" class="mx-2" label="Display as featured offer?"></v-checkbox>
                                        </span>
                                            </template>
                                            <span>Can this offer be displayed as a featured offer in the top section publicly and for tourists?</span>
                                        </v-tooltip>
                                    </v-row>
                                </v-col>
                            </v-flex>

                            <!-- notes -->
                            <v-flex xs12 sm12 md12 lg12 xl12>
                                <v-label>Notes about this offer</v-label>
                                <tiptap-vuetify
                                        v-model="notes"
                                        :extensions="extensions"
                                        placeholder="Add few notes about your offer…"
                                />
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer />
                    <v-btn text color="error" @click="closeForm" :disabled="btn_save_disabled">Cancel</v-btn>
                    <v-btn text color="success" @click="saveForm" :disabled="btn_save_disabled">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="preview_offer">
            <preview-offer :offer="preview_offer" :hide_actions="true" @closeOffer="closeOfferPreview" />
        </v-dialog>
        <v-dialog v-model="delete_dialog" persistent max-width="290">
            <v-card>
                <v-card-title>Are you sure you want to delete "{{ deleteOffertitle }}"?</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-card-subtitle>Your offer will no longer appear in promotions and bundle offers after this action.</v-card-subtitle>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary darken-1" text @click="cancelOfferDelete">No</v-btn>
                    <v-btn color="error darken-1" @click="deleteOfferConfirmed">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <form-helpers :snackbar_visible="snackbar"
                      :snack_color="snack_color"
                      :snack_message="snack_message"
                      :saving="saving" />
    </v-container>
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
    import originalPlaceholderImage from "./assets/placeholder-img.jpg";
    import PreviewOffer from '../tourist/custom/OfferDetails'

    export default {
        components: {
            TiptapVuetify,
            FormHelpers,
            Errors,
            PreviewOffer
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
            editedObject: {
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
            },
            deleteOffer: null,
            deleteOffertitle: '',
            valute: ''
        }),
        methods: {
            getOffers() {
                let that = this
                axios.get('/api/offers')
                    .then(response => {
                        that.offers = response.data.data
                        that.getValuteHint()
                    })
                    .catch(error => {
                        console.log('error fetching offers')
                    })
                    .then(() => {

                    })
            },
            getValuteHint() {
                this.valute = '[please select a currency from your profile settings]'
                if (this.$store.state.session.valute)
                    this.valute = this.$store.state.session.valute.sign
            },
            showForm() {
                this.errors = []
                this.dialog_title = 'Create new offer'
                this.dialog = true
            },
            editOffer(offer) {
                this.id = offer.id
                this.title = offer.title
                this.short_description = offer.short_description
                this.long_description = offer.long_description
                this.placeholderImage = this.$store.state.promo_images_path + offer.promo_image
                this.owner = offer.owner_id
                this.real_price = offer.real_price
                this.offered_price = offer.offered_price
                this.include_global = offer.include_global
                this.featured = offer.featured
                this.notes = offer.notes

                this.errors = []
                this.dialog_title = 'Edit offer "' + offer.title + '"'
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
                this.saving = true
                this.snackbar = false

                let that = this
                axios.post('/api/offers/delete/' + this.deleteOffer.id)
                    .then(response => {
                        that.getOffers()

                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'
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
                console.log(offer)
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
                this.saving = this.btn_save_disabled = true
                this.snackbar = false
                console.log('saving')

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
                this.editedObject = {
                    id: this.id,
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

                this.images = []
                this.placeholderImage = originalPlaceholderImage

                this.editedObject = {
                    id: this.id,
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
            }
        },
        mounted() {
            console.log('offers Component mounted.')
            this.getOffers()
        }
    }
</script>

<style scoped>
    .real-price-text {
        text-decoration: line-through red;
    }
</style>
