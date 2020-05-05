<template>
    <div>
        <crud-index :crud-data="contractlengths"></crud-index>
    </div>
</template>

<script>
    import CrudIndex from '../Index'
    import Form from './Form';
    import List from './List';

    export default {
        name: "Crud",
        components: {
            CrudIndex
        },
        data: () => ({
            contractlengths: {
                apiUrl: 'contractlengths',
                apiSaveUrl: 'contractlengths/save',
                apiDeleteUrl: 'contractlengths/delete/',

                // data table options
                headers: [
                    {text: 'Name', value: 'name', align: 'left', sortable: false},
                    {text: 'Duration (months)', value: 'duration_label', sortable: false,},
                    {text: 'Price', value: 'price_label', sortable: false,},
                    {text: '', align: 'right', value: 'action', sortable: false},
                ],
                form: Form,
                list: List,
                newItemTitle: 'New contract length',
                formTitle: 'Contract Lengths',
                editedItem: {
                    id: 0,
                    name: '',
                    duration: 0,
                    price: 0.0,
                    valute_id: 0,
                    created_at: '',
                    updated_at: ''
                },
                defaultItem: {
                    id: 0,
                    name: '',
                    duration: 0,
                    price: 0.0,
                    valute_id: 0,
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