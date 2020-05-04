<template>
    <div>
        <crud-index :crud-data="valutes"></crud-index>
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
            valutes: {
                apiUrl: 'valutes',
                apiSaveUrl: 'valutes/save',
                apiDeleteUrl: 'valutes/delete/',

                // data table options
                headers: [
                    {text: 'Name', value: 'name', align: 'left', sortable: false},
                    {text: 'Sign', value: 'sign', align: 'left', sortable: false},
                    {text: 'Created', value: 'created_at', sortable: false,},
                    {text: '', align: 'right', value: 'action', sortable: false},
                ],
                form: Form,
                newItemTitle: 'New valute',
                formTitle: 'Valutes',
                editedItem: {
                    id: 0,
                    name: '',
                    sign: '',
                    created_at: '',
                    updated_at: ''
                },
                defaultItem: {
                    id: 0,
                    name: '',
                    sign: '',
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