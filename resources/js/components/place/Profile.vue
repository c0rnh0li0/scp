<template>
    <v-container fluid>
        <v-row class="mx-2">
            <v-col cols="12" id="map-container">
                <div id="autocomplete-container">
                    <v-row class="mx-2">
                        <v-col class="align-center justify-space-between" cols="12">
                            <gmap-autocomplete
                                    class="introInput"
                                    placeholder="Search..."
                                    @place_changed="setPlace">
                            </gmap-autocomplete>
                        </v-col>
                    </v-row>
                </div>

                <v-divider></v-divider>
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

            <v-col class="text-center justify-space-between" cols="2">
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
            <v-col class="justify-center d-flex align-center" cols="10">
                <v-text-field v-model="name" label="Name" :error-messages="errors.name" />
            </v-col>
            <v-col cols="6">
                <v-text-field
                        prepend-icon="mdi-mail"
                        label="Email"
                        disabled
                        v-model="email"
                />
            </v-col>
            <v-col cols="6">
                <v-text-field
                        type="tel"
                        prepend-icon="mdi-phone"
                        label="Telephone"
                        v-model="phone"
                />
            </v-col>
            <v-col cols="6">
                <v-text-field
                        prepend-icon="mdi-earth"
                        label="Website"
                        v-model="website"
                />
            </v-col>
            <v-col cols="6">
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
            <v-col cols="6">
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
            <v-col cols="6">
                <v-select
                        prepend-icon="mdi-format-list-checkbox"
                        v-model="subcategory"
                        ref="subcategory"
                        :items="subcategories"
                        value="subcategory"
                        item-text="name"
                        item-value="id"
                        label="Subcategory"
                ></v-select>
            </v-col>

            <v-col cols="12">
                <tiptap-vuetify
                        v-model="description"
                        :extensions="extensions"
                        placeholder="Describe your place here…"
                />
            </v-col>

            <v-col cols="12">
                <v-text-field
                        prepend-icon="mdi-city"
                        label="Address"
                        v-model="address"
                        :error-messages="errors.address"
                />
            </v-col>

            <v-col cols="6">
                <v-text-field
                        prepend-icon="mdi-longitude"
                        label="Longitude"
                        v-model="longitude"
                        :error-messages="errors.longitude"
                />
            </v-col>
            <v-col cols="6">
                <v-text-field
                        prepend-icon="mdi-latitude"
                        label="Latitude"
                        v-model="latitude"
                        :error-messages="errors.latitude"
                />
            </v-col>
        </v-row>
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

        <v-btn bottom
               color="success"
               ref="saveBtn"
               :disabled="btn_save_disabled"
               dark
               fab
               fixed
               right
               @click="saveData">
            <v-icon>mdi-content-save</v-icon>
        </v-btn>
        <form-helpers :snackbar_visible="snackbar"
                      :snack_color="snack_color"
                      :snack_message="snack_message"
                      :saving="saving"></form-helpers>
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
    import placeholderImage from "./assets/placeholder-img.jpg";
    import { mapState } from 'vuex'

    export default {
        components: {
            TiptapVuetify,
            FormHelpers
        },
        watch: {
            sessionData(newVal, oldVal) {
                this.setPageData(newVal)
            },
            lookupData(newVal, oldVal) {
                console.log('lookups loaded')
                this.setLookupData(newVal)
            },
        },
        computed:
            mapState({
                lookupData: state => state.lookups,
                sessionData: state => state.session
            })
        ,
        data: () => ({
            bottomSheet: false,
            saving: false,
            btn_save_disabled: false,
            loading: false,

            errors: [],

            snackbar: false,
            snack_message: '',
            snack_color: '',
            snack_timeout: 2000,

            progress: false,

            city: -1,
            address: '',
            longitude: 0.0,
            latitude: 0.0,
            name: '',
            email: '',
            phone: '',
            website: '',
            description: '',
            picture: '',
            category: -1,
            subcategory: -1,

            cities: [],
            categories: [],
            subcategories: [],
            profileData: {},

            suggested_place: null,
            suggestion_address: '',
            suggestion_name: '',
            suggestion_phone: '',
            suggestion_website: '',

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
            images: [],
            activeImageUploads: {},
            placeholderImage,

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
        }),
        methods: {
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
            categoryChanged() {
                this.subcategories = this.$store.state.lookups.categories.find(cat => cat.id == this.category).children
                this.subcategory = this.subcategories[0].id
            },
            categoriesLoaded() {
                console.log('categoriesLoaded')
            },
            setLookupData(data) {
                this.categories = data.categories
                this.cities = data.cities

                let parent_category = this.categories.find(cat => cat.id == this.category)
                if (parent_category) {
                    this.subcategories = parent_category.children
                    this.subcategory = this.subcategories[0].id
                }
            },
            setPageData(data) {
                this.profileData = data

                this.name = data.user.name
                this.email = data.user.email
                this.phone = data.phone
                this.description = data.description
                this.picture = data.picture ? this.$store.state.avatars_path + data.picture : this.placeholderImage
                this.placeholderImage = data.picture ? this.$store.state.avatars_path + data.picture : this.placeholderImage

                this.city = data.location ? data.location.city_id : 1
                this.address = data.location ? data.location.address : ''
                this.longitude = data.location ? data.location.longitude != '' ? parseFloat(data.location.longitude) : 0.0 : 0.0
                this.latitude = data.location ? data.location.latitude != '' ? parseFloat(data.location.latitude) : 0.0 : 0.0
                this.category = data.location && data.location.category_id != '' ? data.location.category_id : -1
                this.subcategory = data.location && data.location.subcategory_id != '' ? data.location.subcategory_id : -1

                if (data.location && this.longitude != 0.0 && this.latitude != 0.0) {
                    this.defaultCenter = {
                        lat: this.latitude,
                        lng: this.longitude
                    }
                }

                this.markers.push({
                    position: this.defaultCenter
                })
            },
            async saveData() {
                this.saving = this.btn_save_disabled = true
                this.snackbar = false

                console.log('saving')

                if (!this.profileData.user)
                    this.profileData = this.sessionData

                this.profileData.user.name = this.name

                this.profileData.phone = this.phone
                this.profileData.description = this.description
                this.profileData.picture = this.images.length ? this.images[0] : null
                this.profileData.website = this.website

                if (!this.profileData.location)
                    this.profileData.location = {}

                this.profileData.location.longitude = this.longitude != '' ? this.longitude : 0.0
                this.profileData.location.latitude = this.latitude != '' ? this.latitude : 0.0
                this.profileData.location.address = this.address
                this.profileData.location.city_id = this.city
                this.profileData.location.category_id = this.category > -1 ? this.category : 0
                this.profileData.location.subcategory_id = this.subcategory > -1 ? this.subcategory : 0

                let stringified = JSON.stringify(this.profileData)
                this.profileData.method = 'POST'
                this.profileData.stringData = stringified

                let formData = new FormData()
                formData.append('jsonstring', this.profileData.stringData)
                if (this.profileData.picture) {
                    formData.append('picture', this.profileData.picture, this.profileData.picture.name)
                }

                // add validation fields here:
                formData.append('name', this.profileData.user.name)
                formData.append('longitude', this.profileData.location.longitude)
                formData.append('latitude', this.profileData.location.latitude)
                formData.append('address', this.profileData.location.address)
                formData.append('city_id', this.profileData.location.city_id)
                formData.append('category_id', this.profileData.location.category_id)
                /********************************************/

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                formData.append('_method', 'POST')

                let that = this
                axios.post('/api/userdetails/save/' + this.profileData.id, formData, requestOptions)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'
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
            }
        },
        mounted() {
            console.log('Profile Component mounted, lookups.', this.$store.state.lookups)
            console.log('Profile Component mounted, session.', this.$store.state.session)
            if (this.$store.state.session.user)
                this.setPageData(this.$store.state.session)

            this.setLookupData(this.$store.state.lookups);
        },
        created() {}
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