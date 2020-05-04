<template>
    <div>
        <crud-index :crud-data="languages"></crud-index>
    </div>
</template>

<script>
    import CrudIndex from '../Index'
    import Form from './Form.vue';

    export default {
        name: "Crud",
        components: {
            CrudIndex
        },
        data: () => ({
            languages: {
                apiUrl: 'languages',
                apiSaveUrl: 'languages/save',
                apiDeleteUrl: 'languages/delete/',

                // data table options
                headers: [
                    {text: 'Name', value: 'name', align: 'left', sortable: false},
                    {text: 'Locale', value: 'locale', align: 'left', sortable: false},
                    {text: 'Created', value: 'created_at', sortable: false,},
                    {text: '', align: 'right', value: 'action', sortable: false},
                ],
                form: Form,
                newItemTitle: 'New language',
                formTitle: 'Languages',
                editedItem: {
                    id: 0,
                    name: '',
                    locale: '',
                    created_at: '',
                    updated_at: ''
                },
                defaultItem: {
                    id: 0,
                    name: '',
                    locale: '',
                    created_at: '',
                    updated_at: ''
                },
            }
        }),
        methods: {
            editItem(item) {

            },
            deleteItem(item) {

            }
        },
        mounted() {}
    }
</script>

<style scoped>

</style>