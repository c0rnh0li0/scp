<template>
    <v-container grid-list-xl>
        <v-layout row wrap>
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
                            disabled
                            v-model="email"
                    />
                </v-col>
            </v-flex>

            <!-- country -->
            <v-flex xs12 sm12 md6 lg6 xl6>
                <v-col cols="12">
                    <v-autocomplete
                            prepend-icon="mdi-earth"
                            v-model="country"
                            value="country"
                            :items="$store.state.lookups.countries"
                            :filter="countryFilter"
                            item-text="name"
                            item-value="id"
                            label="Country"
                            @change="countryChanged"
                            :error-messages="errors.country_id"
                    ></v-autocomplete>
                </v-col>
            </v-flex>

            <!-- city -->
            <v-flex xs12 sm12 md6 lg6 xl6>
                <v-col cols="12">
                    <v-combobox
                            prepend-icon="mdi-earth"
                            v-model="city"
                            value="city"
                            :items="cities"
                            :filter="cityFilter"
                            item-text="name"
                            item-value="id"
                            label="City"
                            @blur="cityChanged"
                            :error-messages="errors.city_id"
                    ></v-combobox>
                </v-col>
            </v-flex>

            <!-- address -->
            <v-flex xs12 sm12 md6 lg6 xl6>
                <v-col cols="12">
                    <v-text-field
                            prepend-icon="mdi-city"
                            label="Address"
                            v-model="address"
                            :error-messages="errors.address"
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

            <!-- address -->
            <v-flex xs12 sm12 md6 lg6 xl6>
                <v-col cols="12">
                    <v-select
                            prepend-icon="mdi-gender-male-female"
                            label="Gender"
                            :items="$store.state.lookups.genders"
                            item-text="name"
                            item-value="id"
                            v-model="gender"
                            :error-messages="errors.gender_id"
                    />
                </v-col>
            </v-flex>

            <!-- telephone -->
            <v-flex xs12 sm12 md6 lg6 xl6>
                <v-col cols="12">
                    <v-text-field
                            type="password"
                            prepend-icon="mdi-lock-question"
                            label="Password"
                            v-model="password"
                            readonly
                            @click="openPasswordModal"
                    />
                </v-col>
            </v-flex>

            <!-- description -->
            <v-flex xs12 sm12 md12 lg12 xl12>
                <v-label>Description</v-label>
                <v-col cols="12">
                    <tiptap-vuetify
                            v-model="description"
                            :extensions="extensions"
                            placeholder="Describe yourself here…"
                    />
                </v-col>
            </v-flex>
        </v-layout>

        <v-dialog v-model="password_dialog" persistent max-width="400">
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
    import PasswordForm from './custom/PasswordForm'
    import { mapState } from 'vuex'

    export default {
        name: "Profile",
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
            btn_save_disabled: false,
            loading: false,

            errors: [],

            // form helpers stuff
            saving: false,
            snackbar: false,
            snack_message: '',
            snack_color: '',

            progress: false,

            password_dialog: false,

            city: '',
            country: -1,
            address: '',
            name: '',
            email: '',
            phone: '',
            gender: -1,
            description: '',
            picture: '',
            password: '*****************',
            countries: [],
            cities: [],
            profileData: {},

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
        }),
        methods: {
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
                this.countries = data.countries

                if (this.country > -1) {
                    this.cities = this.$store.state.lookups.countries.find(cat => cat.id == this.country).cities
                    let foundCity = this.cities.find(cit => cit.id == this.city);
                    if (foundCity)
                        this.city = foundCity.name
                }
            },
            // TODO: mark city as selected on load, based on country
            setPageData(data) {
                this.profileData = data

                this.name = data.user.name
                this.email = data.user.email
                this.phone = data.phone
                this.description = data.description
                this.gender = data.gender ? data.gender.id : -1
                this.picture = data.picture ? this.$store.state.avatars_path + data.picture : this.placeholderImage
                this.placeholderImage = data.picture ? this.$store.state.avatars_path + data.picture : this.placeholderImage

                this.city = data.location ? data.location.city_id : ''
                this.country = data.location ? data.location.country_id : -1

                this.address = data.location ? data.location.address : ''
            },
            async saveData() {
                this.saving = this.btn_save_disabled = true
                this.snackbar = false

                if (!this.profileData.user)
                    this.profileData = this.sessionData

                this.profileData.user.name = this.name

                this.profileData.phone = this.phone
                this.profileData.gender_id = this.gender
                this.profileData.description = this.description
                this.profileData.picture = this.images.length ? this.images[0] : null

                if (!this.profileData.location)
                    this.profileData.location = {}

                this.profileData.location.address = this.address
                this.profileData.location.country_id = this.country
                this.profileData.location.city_id = this.city.id ? this.city.id : this.getCity(this.city)

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
                formData.append('is_company', 0)
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
            countryChanged() {
                this.cities = this.$store.state.lookups.countries.find(cat => cat.id == this.country).cities
                if (this.cities.length)
                    this.city = this.cities[0].name
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
                let city = this.cities.find(c => c.name.toLowerCase() == cityName.toLowerCase())

                if (!city || !city.id)
                    return cityName
                else
                    return city.id
            }
        },
        async mounted() {
            console.log('Profile Component mounted, lookups.', this.$store.state.lookups)
            console.log('Profile Component mounted, session.', this.$store.state.session)

            /*if (this.$store.state.session.user)
                this.setPageData(this.$store.state.session)

            this.setLookupData(this.$store.state.lookups);*/
        },
        created() {}
    }
</script>

<style scoped>

</style>