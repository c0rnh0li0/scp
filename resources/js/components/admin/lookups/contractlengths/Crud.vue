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
        computed: {
            contractlengths() {
                return {
                    apiUrl: 'contractlengths',
                    apiSaveUrl: 'contractlengths/save',
                    apiDeleteUrl: 'contractlengths/delete/',

                    // data table options
                    form: Form,
                    headers: [
                        {text: this.$t('message.sections.lookups.contract_lengths.headers.name'), value: 'name', align: 'left', sortable: false},
                        {text: this.$t('message.sections.lookups.contract_lengths.headers.duration'), value: 'duration_label', sortable: false,},
                        {text: this.$t('message.sections.lookups.contract_lengths.headers.price'), value: 'price_label', sortable: false,},
                        {text: '', align: 'right', value: 'action', sortable: false},
                    ],
                    list: List,
                    sectionTitle: this.$i18n.t('message.sections.lookups.contract_lengths.section_title'),
                    newItemTitle: this.$i18n.t('message.sections.lookups.contract_lengths.btn_new_title'),
                    formTitleNew: this.$i18n.t('message.sections.lookups.contract_lengths.form.form_title_new'),
                    formTitleEdit: this.$i18n.t('message.sections.lookups.contract_lengths.form.form_title_edit'),
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
            }
        },
        data: function() {
            return {}
        },
        methods: {},
        mounted() {},
        created() {}
    }
</script>

<style scoped>

</style>