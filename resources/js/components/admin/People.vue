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

                    <v-toolbar-title>{{ $t('message.sections.people.section_title') }}</v-toolbar-title>
                    <v-spacer></v-spacer>

                    <v-dialog v-model="dialog" max-width="1000px" scrollable :fullscreen="$vuetify.breakpoint.mdAndDown">
                        <template v-slot:activator="{ on }">
                            <v-btn color="success" dark class="mb-2" v-on="on">{{ $t('message.sections.people.btn_new_title') }}</v-btn>
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
                                    <!-- avatar -->
                                    <v-flex xs12 sm12 md2 lg2 xl2>
                                        <v-col class="text-center justify-space-between" cols="12">
                                            <v-label @click.stop="pickFile">{{ $t('message.sections.people.form.fields.avatar') }}</v-label>

                                            <input type="file"
                                                   style="display: none;"
                                                   name="avatar"
                                                   ref="image"
                                                   accept="image/*"
                                                   @change="onFilePicked">

                                            <v-spacer></v-spacer>
                                            <v-avatar size="100">
                                                <v-img :src="placeholderImage" width="100" height="100" class="avatar-img" @click.stop="pickFile" aspect-ratio="1"></v-img>
                                            </v-avatar>
                                        </v-col>
                                    </v-flex>

                                    <!-- profile name -->
                                    <v-flex xs12 sm12 md5 lg5 xl5 class="justify-center d-flex align-center">
                                        <v-col cols="12">
                                            <v-text-field v-model="name" :label="$t('message.sections.people.form.fields.name')" :error-messages="errors.name" />
                                        </v-col>
                                    </v-flex>

                                    <!-- email: readonly/disabled (used for login, unique) -->
                                    <v-flex xs12 sm12 md5 lg5 xl5 class="justify-center d-flex align-center">
                                        <v-col cols="12">
                                            <v-text-field
                                                    prepend-icon="mdi-mail"
                                                    :label="$t('message.sections.people.form.fields.email')"
                                                    :disabled="editedIndex > -1"
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
                                                    :label="$t('message.sections.people.form.fields.country')"
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
                                                    :label="$t('message.sections.people.form.fields.city')"
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
                                                    :label="$t('message.sections.people.form.fields.address')"
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
                                                    :label="$t('message.sections.people.form.fields.phone')"
                                                    v-model="phone"
                                            />
                                        </v-col>
                                    </v-flex>

                                    <!-- address -->
                                    <v-flex xs12 sm12 md6 lg6 xl6>
                                        <v-col cols="12">
                                            <v-select
                                                    prepend-icon="mdi-gender-male-female"
                                                    :label="$t('message.sections.people.form.fields.gender')"
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
                                                    :label="$t('message.sections.people.form.fields.password')"
                                                    v-model="password"
                                                    :readonly="editedIndex > -1"
                                                    @click="openPasswordModal"
                                            />
                                        </v-col>
                                    </v-flex>

                                    <!-- description -->
                                    <v-flex xs12 sm12 md12 lg12 xl12>
                                        <div class="pl-3">
                                            <v-label>{{ $t('message.sections.people.form.fields.description') }}</v-label>
                                        </div>

                                        <v-col cols="12">
                                            <tiptap-vuetify
                                                    v-model="description"
                                                    :extensions="extensions"
                                                    :placeholder="$t('message.sections.people.form.fields.description_placeholder')"
                                            />
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
                        @click="askDeletePeople(item)"
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
                <v-card-title>{{ $t('message.global.msg.delete_ask') }} "{{ deletePeopletitle }}"?</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-card-subtitle>"{{ deletePeopletitle }}" {{ $t('message.global.msg.delete_ask_msg') }}.</v-card-subtitle>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary darken-1" text @click="cancelPeopleDelete">{{ $t('message.global.btn_no') }}</v-btn>
                    <v-btn color="error darken-1" @click="deletePeopleConfirmed">{{ $t('message.global.btn_yes') }}</v-btn>
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
    import placeholderImage from "./../tourist/assets/placeholder-img.jpg";
    import PasswordForm from './../tourist/custom/PasswordForm'

    export default {
        name: "People",
        components: {
            TiptapVuetify,
            FormHelpers,
            PasswordForm
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? this.$i18n.t('message.sections.people.form.form_title_new') : this.$i18n.t('message.sections.people.form.form_title_edit') + '"' + this.editedItem.user.name + '"'
            },
            headers() {
                return [
                    {text: this.$t('message.sections.people.headers.name'), align: 'left', sortable: true, value: 'user.name',},
                    {text: this.$t('message.sections.people.headers.email'), value: 'user.email', sortable: true,},
                    {text: this.$t('message.sections.people.headers.type'), value: 'type.name', sortable: false,},
                    {text: this.$t('message.sections.people.headers.created'), value: 'user.created_at', sortable: true,},
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
        },
        data () {
            return {
                api: 'people',
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

                delete_dialog: false,
                deletePeople: '',
                deletePeopletitle: '',

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

                // image upload component
                images: [],
                countries: [],
                cities: [],
                activeImageUploads: {},
                placeholderImage,
                defaultPlaceholderImage: placeholderImage,

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
                    'valute': '',
                    'modified_by': '',
                    'deleted_by': '',
                    'created_at': '',
                    'updated_at': '',
                    'deleted_at': '',
                    'is_company': 0
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
                    'valute': '',
                    'modified_by': '',
                    'deleted_by': '',
                    'created_at': '',
                    'updated_at': '',
                    'deleted_at': '',
                    'is_company': 0
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

                this.address = this.editedItem.location ? this.editedItem.location.address : ''
                this.name = this.editedItem.user ? this.editedItem.user.name : ''
                this.email = this.editedItem.user ? this.editedItem.user.email : ''
                this.phone = this.editedItem.phone
                this.gender = this.editedItem.gender ? this.editedItem.gender.id : -1
                this.description = this.editedItem.description
                this.placeholderImage = this.picture = this.editedItem.picture != '' ? this.$store.state.avatars_path + this.editedItem.picture : this.placeholderImage
                this.password = '*****************'

                this.country = this.editedItem.location ? this.editedItem.location.country_id : -1
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
            },
            editItem (item) {
                this.setEditedItem(item)
                this.dialog = true
            },

            askDeletePeople(place) {
                this.deletePeople = place
                this.deletePeopletitle = place.user.name
                this.delete_dialog = true
            },
            cancelPeopleDelete() {
                this.delete_dialog = false
                this.deletePeople = null
                this.deletePeopletitle = ''
            },
            deletePeopleConfirmed() {
                this.deleteItem(this.deletePeople)
            },
            deleteItem (item) {
                const index = this.items.indexOf(item)

                this.saving = true
                this.snackbar = false

                let that = this
                axios.post('/api/people/delete/' + this.deletePeople.id)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        that.items.splice(index, 1)

                        that.deletePeople = null
                        that.deletePeopletitle = ''
                        that.delete_dialog = false
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
                this.editedItem.gender_id = this.gender
                this.editedItem.description = this.description
                this.editedItem.picture = this.images.length ? this.images[0] : null

                if (!this.editedItem.location)
                    this.editedItem.location = {}

                this.editedItem.location.address = this.address
                this.editedItem.location.country_id = this.country
                this.editedItem.location.city_id = this.city.id ? this.city.id : this.getCity(this.city)

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
                formData.append('is_company', 0)
                /********************************************/

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
                const files = e.target.files
                this.images = []
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
    }
</script>

<style scoped>

</style>
