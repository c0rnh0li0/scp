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
                class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
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
                            <v-btn color="success" dark class="mb-2" v-on="on">{{ newItemTitle }}</v-btn>
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
                                <component :is="form" :editedItem="editedItem" ref="form" :errors="errors"></component>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-btn text color="primary" @click="dialog = !dialog">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn text color="success" @click="saveItem">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
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
                        @click="askDeleteItem(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="getData">Reset</v-btn>
            </template>
        </v-data-table>

        <v-dialog v-model="delete_dialog" persistent max-width="290">
            <v-card>
                <v-card-title>Are you sure you want to delete "{{ deleteItemTitle }}"?</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-card-subtitle>"{{ deleteItemTitle }}" will no longer be present in the system.</v-card-subtitle>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary darken-1" text @click="cancelItemDelete">No</v-btn>
                    <v-btn color="error darken-1" @click="deleteItemConfirmed">Yes</v-btn>
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
    import FormHelpers from '../../custom/FormHelpers'

    export default {
        name: "Index",
        components: {
            FormHelpers
        },
        props: {
            crudData: { type: Object, required: false, default: {}}
        },
        watch: {
            dialog (val) {
                val || this.close()
            },
            crudData(newVal, oldVal) {
                this.setCrudData(newVal)
                return newVal
            },
            options: {
                handler () {
                    this.getData()
                        .then(data => {
                            this.items = data.items
                            this.totalItems = data.total
                        })
                },
                deep: true,
            },
            search(newVal, oldVal){
                this.getData()
                    .then(data => {
                        this.items = data.items
                        this.totalItems = data.total
                    })
            },
        },
        data: () => ({
            // api urls
            apiUrl: '',
            apiSaveUrl: '',
            apiDeleteUrl: '',

            // data table options
            headers: [],
            items: [],
            options: {},
            totalItems: 0,
            loading: false,

            dialog: false,
            formTitle: '',
            form: null,
            newItemTitle: '',

            editedIndex: -1,
            editedItem: {},
            defaultItem: {},

            // form helpers stuff
            saving: false,
            snackbar: false,
            snack_message: '',
            snack_color: '',

            errors: [],
            search: '',
            progress: false,

            deleteItem: null,
            deleteItemTitle: '',
            delete_dialog: false
        }),
        methods: {
            setCrudData(data) {
                this.apiUrl = data.apiUrl
                this.apiSaveUrl = data.apiSaveUrl
                this.apiDeleteUrl = data.apiDeleteUrl

                    // data table options
                this.headers = data.headers
                this.form = data.form

                this.newItemTitle = data.newItemTitle
                this.formTitle = data.formTitle
                this.form = data.form

                this.editedItem = data.editedItem
                this.defaultItem = data.defaultItem

                this.getData()
            },
            getData() {
                this.loading = true
                return new Promise((resolve, reject) => {
                    const { sortBy, sortDesc, page, itemsPerPage } = this.options

                    let queryParams = {
                        itemsPerPage: this.options.itemsPerPage,
                        page: this.options.page,
                        sortBy: this.options.sortBy ? this.options.sortBy[0] : 'created_at',
                        dir: this.options.sortDesc ? this.options.sortDesc.length > 0 ? 'DESC' : 'ASC' : 'DESC',
                        q: this.search.length >= 2 ? this.search : ''
                    }

                    axios.get('/api/' + this.apiUrl, {
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
            searchChanged(e) {
                if (this.search.length >= 2)
                    this.loading = true
                    this.getData()
                        .then(data => {
                            this.items = data.items
                            this.totalItems = data.total
                            this.loading = false
                        })
            },
            editItem(item) {
                this.editedIndex = this.items.indexOf(item)
                let defObj = Object.assign({}, this.defaultItem)
                this.editedItem = {...defObj, ...item }
                this.dialog = true
            },
            resetEditedItem() {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            },
            close () {
                this.dialog = false
                setTimeout(() => {
                    this.resetEditedItem()
                }, 300)
            },
            saveItem() {
                this.saving = this.btn_save_disabled = true
                this.snackbar = false

                this.editedItem.method = 'POST'

                let formData = new FormData()
                for (var property in this.editedItem) {
                    formData.append(property, this.editedItem[property]);
                }

                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                formData.append('_method', 'POST')

                let that = this
                axios.post('/api/' + this.apiSaveUrl, formData, requestOptions)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        if (that.editedIndex > -1)
                            Object.assign(that.items[that.editedIndex], response.data.item)
                        else
                            this.items.push(response.data.item)

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
            askDeleteItem(item) {
                this.deleteItem = item
                this.deleteItemTitle = item.name || item.title
                this.delete_dialog = true
            },
            cancelItemDelete() {
                this.delete_dialog = false
                this.deleteItem = null
                this.deleteItemTitle = ''
            },
            deleteItemConfirmed() {
                this.doDeleteItem(this.deleteItem)
            },
            doDeleteItem (item) {
                const index = this.items.indexOf(item)

                this.saving = true
                this.snackbar = false

                let that = this
                axios.post('/api/' + this.apiDeleteUrl + this.deleteItem.id)
                    .then(response => {
                        that.snack_message = response.data.message
                        that.snack_color = response.data.success ? 'success' : 'error'

                        that.items.splice(index, 1)

                        that.cancelItemDelete()
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
        },
        created() {
            this.setCrudData(this.crudData)
        }
    }
</script>

<style scoped>

</style>