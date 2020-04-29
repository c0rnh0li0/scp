<template>
    <div>
        <v-data-table
                :headers="headers"
                :items="items"
                :search="search"
                :options.sync="options"
                :server-items-length="totalItems"
                :loading="loading"
                sort-by="user_details.created_at"
                class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>Places</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Search"
                            single-line
                            hide-details
                            @change="searchChanged"
                    ></v-text-field>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="1000px" scrollable :fullscreen="$vuetify.breakpoint.mdAndDown">
                        <template v-slot:activator="{ on }">
                            <v-btn color="success" dark class="mb-2" v-on="on">New Place</v-btn>
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
                                    <!-- google maps autocomplete -->
                                    <v-flex xs12 sm12 md12 lg12 xl12 class="">
                                        <v-col class="align-center justify-space-between mb-2" cols="12">
                                            <gmap-autocomplete
                                                    class="introInput"
                                                    placeholder="Search for your location..."
                                                    @place_changed="setPlace">
                                            </gmap-autocomplete>
                                            <v-divider></v-divider>
                                        </v-col>
                                    </v-flex>

                                    <!-- google map component -->
                                    <v-flex xs12 sm12 md12 lg12 xl12 class="">
                                        <v-col cols="12" id="map-container">
                                            <gmap-map
                                                    ref="mapRef"
                                                    :center="defaultCenter"
                                                    :zoom="17"
                                                    map-type-id="satellite"
                                                    style="width: 100%; height: 500px;"
                                                    :options="{
                                                       zoomControl: true,
                                                       mapTypeControl: false,
                                                       scaleControl: false,
                                                       streetViewControl: false,
                                                       rotateControl: false,
                                                       fullscreenControl: true,
                                                       disableDefaultUi: true,
                                                       gestureHandling: 'auto'
                                                     }">
                                                <gmap-marker
                                                        :key="index"
                                                        v-for="(m, index) in markers"
                                                        :position="m.position"
                                                        :clickable="true"
                                                        :draggable="true"
                                                        @click="center=m.position"
                                                        @mouseup="markerMoved"
                                                />
                                            </gmap-map>
                                        </v-col>
                                    </v-flex>

                                    <!-- avatar -->
                                    <v-flex xs12 sm12 md2 lg2 xl2>
                                        <v-col class="text-center justify-space-between" cols="12">
                                            <v-label @click.stop="pickFile">Avatar</v-label>

                                            <input type="file"
                                                   style="display: none;"
                                                   name="avatar"
                                                   ref="image"
                                                   accept="image/*"
                                                   @change="onFilePicked">

                                            <v-spacer></v-spacer>
                                            <v-avatar size="100">
                                                <v-img :src="placeholderImage" width="100" height="100" v-if="placeholderImage" class="avatar-img" @click.stop="pickFile" aspect-ratio="1"></v-img>
                                            </v-avatar>
                                        </v-col>
                                    </v-flex>

                                    <!-- profile name -->
                                    <v-flex xs12 sm12 md5 lg5 xl5 class="justify-center d-flex align-center">
                                        <v-col cols="12">
                                            <v-text-field v-model="name" label="Name" :error-messages="errors.name" />
                                        </v-col>
                                    </v-flex>

                                    <!-- email: readonly/disabled (used for login, unique) -->
                                    <v-flex xs12 sm12 md5 lg5 xl5 class="justify-center d-flex align-center">
                                        <v-col cols="12">
                                            <v-text-field
                                                    prepend-icon="mdi-mail"
                                                    label="Email"
                                                    :disabled="editedIndex > -1"
                                                    v-model="email"
                                                    :error-messages="errors.email"
                                            />
                                            <v-text-field
                                                    type="password"
                                                    prepend-icon="mdi-lock-question"
                                                    label="Password"
                                                    v-model="password"
                                                    :readonly="editedIndex > -1"
                                                    :error-messages="errors.password"
                                            />
                                        </v-col>
                                    </v-flex>

                                    <!-- telephone -->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-text-field
                                                    type="tel"
                                                    prepend-icon="mdi-phone"
                                                    label="Telephone"
                                                    v-model="phone"
                                            />
                                        </v-col>
                                    </v-flex>

                                    <!-- website -->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-text-field
                                                    prepend-icon="mdi-earth"
                                                    label="Website"
                                                    v-model="website"
                                            />
                                        </v-col>
                                    </v-flex>

                                    <!-- Valute -->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-select
                                                    prepend-icon="mdi-currency-eur"
                                                    v-model="valute"
                                                    value="valute"
                                                    :items="valutes"
                                                    item-value="id"
                                                    label="Currency"
                                                    :error-messages="errors.valute_id"
                                            >
                                                <template v-slot:item='{item}'> <div v-html='item.name'/> </template>
                                                <template v-slot:selection='{item}'> <div v-html='item.name'/> </template>
                                            </v-select>
                                        </v-col>
                                    </v-flex>

                                    <!-- city -->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-select
                                                    prepend-icon="mdi-earth"
                                                    v-model="city"
                                                    value="city"
                                                    :items="cities"
                                                    item-text="name"
                                                    item-value="id"
                                                    label="City"
                                                    :error-messages="errors.city_id"
                                            ></v-select>
                                        </v-col>
                                    </v-flex>

                                    <!-- category -->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-select
                                                    prepend-icon="mdi-format-list-bulleted-square"
                                                    v-model="category"
                                                    ref="category"
                                                    value="category"
                                                    :items="categories"
                                                    item-text="name"
                                                    item-value="id"
                                                    label="Category"
                                                    @change="categoryChanged"
                                                    :error-messages="errors.category_id"
                                            ></v-select>
                                        </v-col>
                                    </v-flex>

                                    <!-- subcategory -->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-select
                                                    prepend-icon="mdi-format-list-checkbox"
                                                    v-model="subcategory"
                                                    ref="subcategory"
                                                    :items="subcategories"
                                                    value="subcategory"
                                                    item-text="name"
                                                    item-value="id"
                                                    label="Subcategory"
                                                    :error-messages="errors.subcategory_id"
                                            ></v-select>
                                        </v-col>
                                    </v-flex>

                                    <!-- description -->
                                    <v-flex xs12 sm12 md12 lg12 xl12>
                                        <div class="pl-3">
                                            <v-label>Description</v-label>
                                        </div>
                                        <v-col cols="12">
                                            <tiptap-vuetify
                                                    v-model="description"
                                                    :extensions="extensions"
                                                    placeholder="Describe your place here…"
                                            />
                                        </v-col>
                                    </v-flex>


                                    <!-- address -->
                                    <v-flex xs12 sm12 md12 lg12 xl12>
                                        <v-col cols="12">
                                            <v-text-field
                                                    prepend-icon="mdi-city"
                                                    label="Address"
                                                    v-model="address"
                                                    :error-messages="errors.address"
                                            />
                                        </v-col>
                                    </v-flex>

                                    <!-- longitude-->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-text-field
                                                    prepend-icon="mdi-longitude"
                                                    label="Longitude"
                                                    v-model="longitude"
                                                    :error-messages="errors.longitude"
                                            />
                                        </v-col>
                                    </v-flex>

                                    <!-- latitude-->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-text-field
                                                    prepend-icon="mdi-latitude"
                                                    label="Latitude"
                                                    v-model="latitude"
                                                    :error-messages="errors.latitude"
                                            />
                                        </v-col>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>

                            <v-card-actions>
                                <v-btn text color="primary" @click="close">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn text color="success" @click="save">Save</v-btn>
                            </v-card-actions>
                        </v-card>

                        <v-bottom-sheet v-model="bottomSheet">
                            <v-sheet height="auto">
                                <v-card class="mx-auto"
                                        max-width="300"
                                        tile>
                                    <v-card-text>
                                        This place contains informations about the place you chose. Would you like to use that data?
                                    </v-card-text>

                                    <v-list disabled>
                                        <v-list-item-group color="primary">
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-bank</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title v-text="suggestion_name"></v-list-item-title>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-city</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title v-text="suggestion_address"></v-list-item-title>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-earth</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title v-text="suggestion_website"></v-list-item-title>
                                                </v-list-item-content>
                                            </v-list-item>
                                            <v-list-item>
                                                <v-list-item-icon>
                                                    <v-icon>mdi-phone</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title v-text="suggestion_phone"></v-list-item-title>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>
                                    </v-list>

                                    <v-card-actions>
                                        <v-btn class="mt-6"
                                               text
                                               color="red"
                                               @click="bottomSheet = !bottomSheet">No</v-btn>
                                        <v-btn class="mt-6"
                                               text
                                               color="success"
                                               @click="useSuggestedData">Yes</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-sheet>
                        </v-bottom-sheet>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.user.name="{ item }">
                <v-avatar size="25" class="mr-2">
                    <v-img :src="$store.state.avatars_path + item.picture" width="25" height="25" aspect-ratio="1"></v-img>
                </v-avatar>
                {{ item.user.name }}
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
                        @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="getDataFromApi">Reset</v-btn>
            </template>
        </v-data-table>
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
    import placeholderImage from "./../tourist/assets/placeholder-img.jpg";

    export default {
        name: "Places",
        components: {
            TiptapVuetify,
            FormHelpers
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New place' : 'Edit place "' + this.editedItem.user.name + '"'
            },
            lookupData () {
                this.setLookupData(this.$store.state.lookups)
                return this.$store.state.lookups
            }
        },
        watch: {
            dialog(val) {
                val || this.close()
            },
            lookupData(newVal, oldVal) {
                this.setLookupData(newVal)
            },
            options: {
                handler() {
                    this.getDataFromApi()
                        .then(data => {
                            this.items = data.items
                            this.totalItems = data.total
                        })
                },
                deep: true,
            },
            search(newVal, oldVal) {
                this.getDataFromApi()
                    .then(data => {
                        this.items = data.items
                        this.totalItems = data.total
                    })
            },
        },
        data () {
            return {
                api: 'place',
                dialog: false,
                totalItems: 0,
                items: [],
                loading: true,
                options: {},
                headers: [
                    {text: 'Name', align: 'left', sortable: true, value: 'user.name',},
                    {text: 'Email', value: 'user.email', sortable: true,},
                    {text: 'Type', value: 'type.name', sortable: false,},
                    {text: 'Since', value: 'user.created_at', sortable: true,},
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

                password_dialog: false,
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

                city: '',
                country: -1,
                address: '',
                name: '',
                email: '',
                phone: '',
                gender: -1,
                description: '',
                picture: '',
                password: '',
                longitude: 0.0,
                latitude: 0.0,
                category: -1,
                subcategory: -1,
                valute: '',
                website: '',

                // image upload component
                images: [],
                countries: [],
                cities: [],
                categories: [],
                subcategories: [],
                valutes: [],
                activeImageUploads: {},
                placeholderImage,
                defaultPlaceholderImage: placeholderImage,

                suggested_place: null,
                suggestion_address: '',
                suggestion_name: '',
                suggestion_phone: '',
                suggestion_website: '',

                bottomSheet: false,

                initialDefaultCenter: {
                    lat: 41.98611587039809,
                    lng: 21.446163830382503
                },
                defaultCenter: {
                    lat: 41.98611587039809,
                    lng: 21.446163830382503
                },
                markers: [{
                    position: {
                        lat: 41.98611587039809,
                        lng: 21.446163830382503
                    }
                }],

                editedIndex: -1,
                editedItem: {
                    'id': -1,
                    'phone': '',
                    'description': '',
                    'picture': '',
                    'website': '',
                    'user': {
                        'id': -1,
                        'name': '',
                        'email': '',
                        'created_at': '',
                        'updated_at': ''
                    },
                    'type': {
                        'id': -1,
                        'name': '',
                        'created_at': '',
                        'updated_at': '',
                        'modified_by': '',
                        'deleted_by': '',
                        'deleted_at': '',
                        'website': '0'
                    },
                    'gender': {
                        'id': -1,
                        'name': '',
                        'created_at': '',
                        'updated_at': '',
                        'modified_by': '',
                        'deleted_by': '',
                        'deleted_at': ''
                    },
                    'location': {
                        'longitude': '',
                        'latitude': '',
                        'address': '',
                        'number': '',
                        'city_id': -1,
                        'city': {
                            'id': -1,
                            'name': '',
                            'country_id': '',
                            'created_at': '',
                            'updated_at': ''
                        },
                        'country_id': -1,
                        'country': {
                            'id': -1,
                            'code': '',
                            'cities': [],
                            'name': ''
                        },
                        'category_id': '',
                        'subcategory_id': '',
                        'category': '',
                        'subcategory': '',
                        'modified_by': '',
                        'deleted_by': '',
                        'deleted_at': '',
                        'created_at': '',
                        'updated_at': ''
                    },
                    'valute': {
                        'id': -1,
                        'name': '',
                        'sign': '',
                        'created_at': '',
                        'deleted_at': '',
                        'deleted_by': '',
                        'modified_by': '',
                        'updated_at': ''
                    },
                    'modified_by': '',
                    'deleted_by': '',
                    'created_at': '',
                    'updated_at': '',
                    'deleted_at': '',
                    'is_company': 1
                },
                defaultItem: {
                    'id': -1,
                    'phone': '',
                    'description': '',
                    'picture': '',
                    'website': '',
                    'user': {
                        'id': -1,
                        'name': '',
                        'email': '',
                        'created_at': '',
                        'updated_at': ''
                    },
                    'type': {
                        'id': -1,
                        'name': '',
                        'created_at': '',
                        'updated_at': '',
                        'modified_by': '',
                        'deleted_by': '',
                        'deleted_at': '',
                        'website': '0'
                    },
                    'gender': {
                        'id': -1,
                        'name': '',
                        'created_at': '',
                        'updated_at': '',
                        'modified_by': '',
                        'deleted_by': '',
                        'deleted_at': ''
                    },
                    'location': {
                        'longitude': '',
                        'latitude': '',
                        'address': '',
                        'number': '',
                        'city_id': -1,
                        'city': {
                            'id': -1,
                            'name': '',
                            'country_id': '',
                            'created_at': '',
                            'updated_at': ''
                        },
                        'country_id': -1,
                        'country': {
                            'id': -1,
                            'code': '',
                            'cities': [],
                            'name': ''
                        },
                        'category_id': '',
                        'subcategory_id': '',
                        'category': '',
                        'subcategory': '',
                        'modified_by': '',
                        'deleted_by': '',
                        'deleted_at': '',
                        'created_at': '',
                        'updated_at': ''
                    },
                    'valute': {
                        'id': -1,
                        'name': '',
                        'sign': '',
                        'created_at': '',
                        'deleted_at': '',
                        'deleted_by': '',
                        'modified_by': '',
                        'updated_at': ''
                    },
                    'modified_by': '',
                    'deleted_by': '',
                    'created_at': '',
                    'updated_at': '',
                    'deleted_at': '',
                    'is_company': 1
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
            markerMoved(marker) {
                this.longitude = marker.latLng.lng()
                this.latitude = marker.latLng.lat()
            },
            setPlace(place) {
                console.log(place)
                this.defaultCenter = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                }

                this.address = place.formatted_address
                this.longitude = this.defaultCenter.lng
                this.latitude = this.defaultCenter.lat

                this.usePlace(place)
                this.suggestPlaceInfo(place)
            },
            usePlace(place) {
                this.markers = []
                this.markers.push({
                    position: {
                        lat: place.geometry.location.lat(),
                        lng: place.geometry.location.lng(),
                    }
                })
            },
            suggestPlaceInfo(place) {
                this.suggested_place = place

                this.suggestion_address = place.formatted_address ? place.formatted_address : ''
                this.suggestion_name = place.name ? place.name : ''
                this.suggestion_phone = place.international_phone_number ? place.international_phone_number : ''
                this.suggestion_website = place.website ? place.website : ''

                this.bottomSheet = true;
            },
            useSuggestedData() {
                this.bottomSheet = !this.bottomSheet

                this.name = this.suggestion_name
                this.address = this.suggestion_address
                this.phone = this.suggestion_phone
                this.website = this.suggestion_website

                this.resetSuggestions()
            },
            resetSuggestions() {
                this.suggested_place = null
                this.suggestion_website = ''
                this.suggestion_phone = ''
                this.suggestion_address = ''
                this.suggestion_name = ''
            },
            setEditedItem(item) {
                this.editedIndex = this.items.indexOf(item)
                let defObj = Object.assign({}, this.defaultItem)
                this.editedItem = {...defObj, ...item }

                this.address = this.editedItem.location ? this.editedItem.location.address : ''
                this.name = this.editedItem.user ? this.editedItem.user.name : ''
                this.email = this.editedItem.user ? this.editedItem.user.email : ''
                this.phone = this.editedItem.phone
                this.gender = this.editedItem.gender ? this.editedItem.gender.id : -1
                this.website = this.editedItem.website
                this.valute = this.editedItem.valute.id
                this.description = this.editedItem.description
                this.placeholderImage = this.picture = this.editedItem.picture != '' ? this.$store.state.avatars_path + this.editedItem.picture : this.placeholderImage
                this.password = '*****************'

                this.country = this.editedItem.location.country_id
                this.city = this.editedItem.location.city_id
                this.longitude = this.editedItem.location ? this.editedItem.location.longitude != '' ? parseFloat(this.editedItem.location.longitude) : 0.0 : 0.0
                this.latitude = this.editedItem.location ? this.editedItem.location.latitude != '' ? parseFloat(this.editedItem.location.latitude) : 0.0 : 0.0

                this.category = this.editedItem.location.category_id
                this.subcategory = this.editedItem.location.subcategory_id

                if (this.editedItem.location && this.longitude != 0.0 && this.latitude != 0.0) {
                    this.defaultCenter = {
                        lat: this.latitude,
                        lng: this.longitude
                    }
                }

                this.markers.push({
                    position: this.defaultCenter
                })

                if (this.country > -1) {
                    this.city = this.editedItem.location.city_id

                    let citiesByCountry = this.$store.state.lookups.countries.find(cat => cat.id == this.country)
                    if (citiesByCountry) {
                        this.cities = citiesByCountry.cities
                        let foundCity = this.cities.find(cit => cit.id == this.city);
                        if (foundCity)
                            this.city = foundCity.name
                    }
                }
                else
                    this.city = ''
            },
            resetEditedItem() {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.setEditedItem(this.editedItem)
                this.placeholderImage = this.defaultPlaceholderImage
                this.editedIndex = -1
                this.images = []

                this.defaultCenter = this.initialDefaultCenter

                this.markers.push({
                    position: this.defaultCenter
                })

                console.log('after update: ', this.editedItem)
            },
            editItem (item) {
                this.setEditedItem(item)
                this.dialog = true
            },

            deleteItem (item) {
                const index = this.items.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.items.splice(index, 1)
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

                if (!this.editedItem.user)
                    this.editedItem.user = {}

                this.editedItem.user.name = this.name
                if (this.editedIndex == -1) {
                    this.editedItem.user.email = this.email
                    this.editedItem.user.password = this.password
                }

                this.editedItem.phone = this.phone
                this.editedItem.description = this.description
                this.editedItem.picture = this.images.length ? this.images[0] : null
                this.editedItem.website = this.website
                this.editedItem.valute = this.valute

                if (!this.editedItem.location)
                    this.editedItem.location = {}

                this.editedItem.location.longitude = this.longitude != '' ? this.longitude : 0.0
                this.editedItem.location.latitude = this.latitude != '' ? this.latitude : 0.0
                this.editedItem.location.address = this.address
                this.editedItem.location.country_id = 131
                this.editedItem.location.city_id = this.getCity(this.city)
                this.editedItem.location.category_id = this.category > -1 ? this.category : 0
                this.editedItem.location.subcategory_id = this.subcategory > -1 ? this.subcategory : 0
                this.editedItem.is_company = 1

                let stringified = JSON.stringify(this.editedItem)
                this.editedItem.method = 'POST'
                this.editedItem.stringData = stringified

                let formData = new FormData()
                formData.append('jsonstring', this.editedItem.stringData)
                if (this.editedItem.picture) {
                    formData.append('picture', this.editedItem.picture, this.editedItem.picture.name)
                }

                // add validation fields here:
                if (this.editedIndex == -1) {
                    formData.append('email', this.editedItem.user.email)
                    formData.append('password', this.editedItem.user.password)
                }

                formData.append('name', this.editedItem.user.name)
                formData.append('longitude', this.editedItem.location.longitude)
                formData.append('latitude', this.editedItem.location.latitude)
                formData.append('address', this.editedItem.location.address)
                formData.append('city_id', this.editedItem.location.city_id)
                formData.append('valute_id', this.editedItem.valute)
                formData.append('category_id', this.editedItem.location.category_id)
                /********************************************/

                formData.append('is_company', 1)

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                formData.append('_method', 'POST')

                let that = this
                axios.post('/api/userdetails/save/' + this.editedItem.id, formData, requestOptions)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        if (that.editedIndex > -1)
                            Object.assign(that.items[that.editedIndex], response.data.user)
                        else
                            this.items.push(response.data.user)

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
                this.images = []
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
            countryFilter(item, queryText, itemText) {
                const textOne = item.name.toLowerCase()
                const searchText = queryText.toLowerCase()

                return textOne.indexOf(searchText) > -1
            },
            cityFilter(item, queryText, itemText) {
                const textOne = item.name.toLowerCase()
                const searchText = queryText.toLowerCase()

                return textOne.indexOf(searchText) > -1
            },
            setLookupData(data) {
                this.categories = data.categories
                this.cities = data.cities
                this.valutes = data.valutes
                this.countries = data.countries

                let parent_category = this.categories.find(cat => cat.id == this.category)
                if (parent_category) {
                    this.subcategories = parent_category.children
                    this.subcategory = this.subcategories[0].id
                }

                if (this.country > -1) {
                    let citiesByCountry = this.$store.state.lookups.countries.find(cat => cat.id == this.country)
                    if (citiesByCountry) {
                        this.cities = citiesByCountry.cities
                        let foundCity = this.cities.find(cit => cit.id == this.city);
                        if (foundCity)
                            this.city = foundCity.name
                    }
                }
            },
            openPasswordModal() {
                this.password_dialog = true
            },
            closePasswordDialog() {
                this.password_dialog = false
            },
            countryChanged() {
                this.cities = this.$store.state.lookups.countries.find(cat => cat.id == this.country).cities
                if (this.cities.length)
                    this.city = this.cities[0].name
            },
            categoryChanged() {
                this.subcategories = this.$store.state.lookups.categories.find(cat => cat.id == this.category).children
                this.subcategory = this.subcategories[0].id
            },
            cityChanged(e) {
                if (!this.cities.find(c => c.name.toLowerCase() == e.target.value.toLowerCase())) {
                    this.cities.push({
                        name: e.target.value,
                        country_id: this.country
                    })
                }
            },
            getCity(cityName) {
                if (!isNaN(cityName))
                    return cityName

                let city = this.cities.find(c => c.name.toLowerCase() == cityName.toLowerCase())

                if (!city || !city.id)
                    return cityName
                else
                    return city.id
            }
        },
        mounted() {
            this.setLookupData(this.$store.state.lookups);
        },
    }
</script>

<style scoped>
    .avatar-lbl {
        color: rgba(0, 0, 0, 0.6);
    }

    .introInput {
        -webkit-box-flex: 1;
        flex: 1 1 auto;
        line-height: 20px;
        padding: 8px 0 8px;
        max-width: 100%;
        min-width: 0px;
        width: 100%;
    }

    .avatar-img {
        display: block;
        max-width:100px;
        max-height:100px;
        width: auto;
        height: auto;
    }
</style>