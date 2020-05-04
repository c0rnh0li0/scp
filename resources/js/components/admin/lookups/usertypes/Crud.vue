<template>
    <div>
        <crud-index :crud-data="usertypes"></crud-index>
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
            usertypes: {
                apiUrl: 'usertypes',
                apiSaveUrl: 'usertypes/save',
                apiDeleteUrl: 'usertypes/delete/',

                // data table options
                headers: [
                    {text: 'Name', value: 'name', align: 'left', sortable: false},
                    {text: 'Created', value: 'created_at', sortable: false,},
                    {text: '', align: 'right', value: 'action', sortable: false},
                ],
                form: Form,
                newItemTitle: 'New user type',
                formTitle: 'User types',
                editedItem: {
                    id: 0,
                    name: '',
                    created_at: '',
                    updated_at: ''
                },
                defaultItem: {
                    id: 0,
                    name: '',
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