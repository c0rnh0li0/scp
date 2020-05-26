<template>
    <div>
        <v-layout row wrap class="pa-2">
            <!-- google maps autocomplete -->
            <v-flex xs12 sm12 md12 lg12 xl12 class="">
                <v-col class="align-center justify-space-between mb-2" cols="12">
                    <gmap-autocomplete
                            class="introInput"
                            :placeholder="$t('message.sections.place.sections.profile.form.fields.location')"
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
                    <v-label @click.stop="pickFile">{{ $t('message.sections.place.sections.profile.form.fields.avatar') }}</v-label>

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
                    <v-text-field v-model="name" :label="$t('message.sections.place.sections.profile.form.fields.name')" :error-messages="errors.name" />
                </v-col>
            </v-flex>

            <!-- email: readonly/disabled (used for login, unique) -->
            <v-flex xs12 sm12 md5 lg5 xl5 class="justify-center d-flex align-center">
                <v-col cols="12">
                    <v-text-field
                            prepend-icon="mdi-mail"
                            :label="$t('message.sections.place.sections.profile.form.fields.email')"
                            disabled
                            v-model="email"
                    />
                    <v-text-field
                            type="password"
                            prepend-icon="mdi-lock-question"
                            :label="$t('message.sections.place.sections.profile.form.fields.password')"
                            v-model="password"
                            readonly
                            @click="openPasswordModal"
                    />
                </v-col>
            </v-flex>

            <!-- telephone -->
            <v-flex xs12 sm12 md6 lg6 xl6>
                <v-col cols="12">
                    <v-text-field
                            type="tel"
                            prepend-icon="mdi-phone"
                            :label="$t('message.sections.place.sections.profile.form.fields.phone')"
                            v-model="phone"
                    />
                </v-col>
            </v-flex>

            <!-- website -->
            <v-flex xs12 sm12 md6 lg6 xl6>
                <v-col cols="12">
                    <v-text-field
                            prepend-icon="mdi-earth"
                            :label="$t('message.sections.place.sections.profile.form.fields.website')"
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
                            :label="$t('message.sections.place.sections.profile.form.fields.valute')"
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
                            :label="$t('message.sections.place.sections.profile.form.fields.city')"
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
                            :label="$t('message.sections.place.sections.profile.form.fields.category')"
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
                            :label="$t('message.sections.place.sections.profile.form.fields.subcategory')"
                    ></v-select>
                </v-col>
            </v-flex>

            <!-- description -->
            <v-flex xs12 sm12 md12 lg12 xl12>
                <div class="pl-3">
                    <v-label>{{ $t('message.sections.place.sections.profile.form.fields.description') }}</v-label>
                </div>
                <v-col cols="12">
                    <tiptap-vuetify
                            v-model="description"
                            :extensions="extensions"
                            :placeholder="$t('message.sections.place.sections.profile.form.fields.description_placeholder')"
                    />
                </v-col>
            </v-flex>


            <!-- address -->
            <v-flex xs12 sm12 md12 lg12 xl12>
                <v-col cols="12">
                    <v-text-field
                            prepend-icon="mdi-city"
                            :label="$t('message.sections.place.sections.profile.form.fields.address')"
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
                            :label="$t('message.sections.place.sections.profile.form.fields.longitude')"
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
                            :label="$t('message.sections.place.sections.profile.form.fields.latitude')"
                            v-model="latitude"
                            :error-messages="errors.latitude"
                    />
                </v-col>
            </v-flex>
        </v-layout>

        <v-bottom-sheet v-model="bottomSheet">
            <v-sheet height="auto">
                <v-card class="mx-auto"
                        max-width="300"
                        tile>
                    <v-card-text>
                        {{ $t('message.sections.place.msg.place_contains_info') }}
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
                               @click="bottomSheet = !bottomSheet">{{ $t('message.global.btn_no') }}</v-btn>
                        <v-btn class="mt-6"
                               text
                               color="success"
                               @click="useSuggestedData">{{ $t('message.global.btn_yes') }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-sheet>
        </v-bottom-sheet>
        <v-dialog v-model="password_dialog" persistent max-width="400" :fullscreen="$vuetify.breakpoint.mdAndDown">
            <password-form @closePasswordDialog="closePasswordDialog" />
        </v-dialog>
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
    import placeholderImage from "./assets/placeholder-img.jpg"
    import PasswordForm from './custom/PasswordForm'
    import { mapState } from 'vuex'

    export default {
        components: {
            TiptapVuetify,
            FormHelpers,
            PasswordForm
        },
        watch: {
            sessionData(newVal, oldVal) {
                this.setPageData(newVal)
            },
            lookupData(newVal, oldVal) {
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
            btn_save_disabled: false,
            loading: false,

            errors: [],

            // form helpers stuff
            saving: false,
            snackbar: false,
            snack_message: '',
            snack_color: '',

            progress: false,

            city: -1,
            address: '',
            longitude: 0.0,
            latitude: 0.0,
            name: '',
            email: '',
            phone: '',
            website: '',
            valute: '',
            description: '',
            picture: '',
            category: -1,
            subcategory: -1,

            cities: [],
            categories: [],
            subcategories: [],
            valutes: [],
            profileData: {},

            password_dialog: false,
            password: '*****************',

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
            categoriesLoaded() {},
            setLookupData(data) {
                this.categories = data.categories
                this.cities = data.cities
                this.valutes = data.valutes

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
                this.valute = data.valute ? data.valute.id : null
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

                if (!this.profileData.user)
                    this.profileData = this.sessionData

                this.profileData.user.name = this.name

                this.profileData.phone = this.phone
                this.profileData.description = this.description
                this.profileData.picture = this.images.length ? this.images[0] : null
                this.profileData.website = this.website
                this.profileData.valute = this.valute

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
                formData.append('valute_id', this.profileData.valute)
                formData.append('category_id', this.profileData.location.category_id)
                /********************************************/

                formData.append('is_company', 1)

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
                        this.$store.dispatch('getSession')
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
            openPasswordModal() {
                this.password_dialog = true
            },
            closePasswordDialog() {
                this.password_dialog = false
            },
        },
        mounted() {
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