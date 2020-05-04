<template>
    <div>
        <crud-index :crud-data="cities"></crud-index>
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
            cities: {
                apiUrl: 'cities',
                apiSaveUrl: 'cities/save',
                apiDeleteUrl: 'cities/delete/',

                // data table options
                headers: [
                    {text: 'Name', value: 'name', align: 'left', sortable: false},
                    {text: 'Country', value: 'country.name', sortable: false,},
                    {text: 'Created', value: 'created_at', sortable: false,},
                    {text: '', align: 'right', value: 'action', sortable: false},
                ],
                form: Form,
                newItemTitle: 'New city',
                formTitle: 'Cities',
                editedItem: {
                    id: 0,
                    name: '',
                    country_id: 0,
                    created_at: '',
                    updated_at: ''
                },
                defaultItem: {
                    id: 0,
                    name: '',
                    country_id: 0,
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