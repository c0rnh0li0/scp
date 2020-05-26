<template>
    <div>
        <v-card>
            <v-card-title>{{ $t('message.sections.settings.settings_title') }}</v-card-title>
            <v-card-subtitle>{{ $t('message.sections.settings.settings_subtitle') }}</v-card-subtitle>
            <v-divider></v-divider>
            <v-card-text>
                <v-layout row wrap class="pa-2">
                    <v-flex xs12 sm12 md6 lg6 xl6 class="justify-center d-flex align-center">
                        <v-col cols="12">
                            <v-text-field prepend-icon="mdi-earth" v-model="site_name" :label="$t('message.sections.settings.labels.site_name')" :error-messages="errors.site_name" />
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col class="text-center justify-space-between" cols="12">
                            <v-label @click.stop="pickFile">{{ $t('message.sections.settings.labels.site_logo') }}</v-label>

                            <input type="file"
                                   style="display: none;"
                                   name="avatar"
                                   ref="image"
                                   accept="image/*"
                                   @change="onFilePicked">

                            <v-spacer></v-spacer>
                            <v-avatar size="200">
                                <v-img :src="placeholderImage" width="200" height="200" class="avatar-img" @click.stop="pickFile" aspect-ratio="1"></v-img>
                            </v-avatar>
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-select
                                    prepend-icon="mdi-page-layout-header-footer"
                                    :label="$t('message.sections.settings.labels.frontpage_template')"
                                    :items="templates"
                                    item-text="name"
                                    item-value="id"
                                    v-model="frontpage_template"
                                    :error-messages="errors.frontpage_template"
                            />
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-text-field prepend-icon="mdi-phone" v-model="phone" :label="$t('message.sections.settings.labels.contact_phone')" :error-messages="errors.phone" />
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-text-field prepend-icon="mdi-mail" v-model="email" :label="$t('message.sections.settings.labels.contact_email')" :error-messages="errors.email" />
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-text-field prepend-icon="mdi-city" v-model="address" :label="$t('message.sections.settings.labels.contact_address')" :error-messages="errors.address" />
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-select
                                    prepend-icon="mdi-translate"
                                    :label="$t('message.sections.settings.labels.default_language')"
                                    :items="languages"
                                    item-text="name"
                                    item-value="id"
                                    v-model="language"
                                    :error-messages="errors.language_id"
                            />
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <span v-on="on">
                                        <v-switch
                                                prepend-icon="mdi-file-document-edit"
                                                v-model="contract_check"
                                                :label="$t('message.sections.settings.labels.contract_check')"
                                        ></v-switch>
                                    </span>
                                </template>
                                <span>{{ $t('message.sections.settings.labels.contract_check_hint') }}</span>
                            </v-tooltip>

                        </v-col>
                    </v-flex>


                    <!-- <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-text-field v-model="longitude" label="Longitude" :error-messages="errors.longitude" />
                        </v-col>
                    </v-flex>

                    <v-flex xs12 sm12 md6 lg6 xl6>
                        <v-col cols="12">
                            <v-text-field v-model="latitude" label="Latitude" :error-messages="errors.latitude" />
                        </v-col>
                    </v-flex> -->
                </v-layout>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <span class="caption">
                    {{ $t('message.sections.settings.labels.last_updated') }}: {{ updated_at }}
                </span>
                <v-spacer></v-spacer>
                <v-btn large color="success" @click="save">{{ $t('message.global.btn_save') }}</v-btn>
            </v-card-actions>
        </v-card>
        <form-helpers :snackbar_visible="snackbar"
                      :snack_color="snack_color"
                      :snack_message="snack_message"
                      :saving="saving"></form-helpers>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import placeholderImage from "./../tourist/assets/placeholder-img.jpg";
    import FormHelpers from '../custom/FormHelpers'

    export default {
        name: "Settings",
        components: {
            FormHelpers
        },
        computed:
            mapState({
                settings: state => state.settings
            })
        ,
        watch: {
            settings(newVal, oldVal) {
                this.setSettings(newVal)
                return newVal
            },
            loading(newVal, oldVal) {
                return newVal
            }
        },
        data: () => ({
            placeholderImage,
            images: [],
            errors: [],
            templates: [
                { id: 1, name: 'Default template' }
            ],
            languages: [],

            // form helpers stuff
            saving: false,
            snackbar: false,
            snack_message: '',
            snack_color: '',

            progress: false,

            id: 1,
            site_name: '',
            site_logo: '',
            frontpage_template: '',
            phone: '',
            address: '',
            email: '',
            contract_check: 0,
            language: 0,
            longitude: '',
            latitude: '',
            created_at: '',
            updated_at: '',

            editedItem: {
                id: 1,
                site_name: '',
                site_logo: '',
                frontpage_template: '',
                phone: '',
                address: '',
                email: '',
                contract_check: 0,
                longitude: '',
                latitude: '',
                created_at: '',
                updated_at: '',
            }
        }),
        methods: {
            setSettings(data) {
                this.editedItem.id = this.id = data ? data.id : ''
                this.editedItem.site_name = this.site_name = data ? data.site_name : ''
                this.editedItem.site_logo = this.site_logo = data ? data.site_logo : ''
                this.editedItem.frontpage_template = this.frontpage_template = data ? data.frontpage_template : 0
                this.editedItem.language_id = this.language = data ? data.language_id : 0
                this.editedItem.phone = this.phone = data ? data.phone : ''
                this.editedItem.address = this.address = data ? data.address : ''
                this.editedItem.email = this.email = data ? data.email : ''
                this.editedItem.contract_check = this.contract_check = data ? data.contract_check : ''
                this.editedItem.longitude = this.longitude = data ? data.longitude : ''
                this.editedItem.latitude = this.latitude = data ? data.latitude : ''
                this.editedItem.created_at = this.created_at = data ? data.created_at : ''
                this.editedItem.updated_at = this.updated_at = data ? data.updated_at : ''

                this.placeholderImage = this.site_logo ? this.$store.state.logo_path + this.site_logo : this.placeholderImage
            },
            save() {
                this.saving = this.btn_save_disabled = true
                this.snackbar = false

                this.editedItem.id = this.id
                this.editedItem.site_name = this.site_name
                this.editedItem.site_logo = this.site_logo
                this.editedItem.frontpage_template = this.frontpage_template
                this.editedItem.phone = this.phone
                this.editedItem.address = this.address
                this.editedItem.email = this.email
                this.editedItem.contract_check = this.contract_check
                this.editedItem.language_id = this.language
                this.editedItem.longitude = this.longitude
                this.editedItem.latitude = this.latitude

                this.editedItem.method = 'POST'

                let formData = new FormData()

                for (var property in this.editedItem) {
                    if (property == 'site_logo' && this.images.length == 1)
                        formData.append(property, this.images[0], this.images[0].name);
                    else
                        formData.append(property, this.editedItem[property]);
                }

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                formData.append('_method', 'POST')

                let that = this
                axios.post('/api/settings/save/', formData, requestOptions)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        this.$store.dispatch('updateSettings', response.data.settings)
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
            getLanguages() {
                axios.get('/api/languages')
                    .then(response => {
                        this.languages = response.data.data
                    })
                    .catch(err => {
                        console.log('Error fetching languages')
                    })
            }
        },
        mounted() {
            this.setSettings(this.$store.state.settings)
            if (this.languages.length == 0)
                this.getLanguages()
        }
    }
</script>

<style scoped>

</style>