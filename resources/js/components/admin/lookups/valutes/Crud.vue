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
        computed: {
            valutes() {
                return {
                    apiUrl: 'valutes',
                    apiSaveUrl: 'valutes/save',
                    apiDeleteUrl: 'valutes/delete/',

                    // data table options
                    form: Form,
                    headers: [
                        {text: this.$t('message.sections.lookups.valutes.headers.name'), value: 'name', align: 'left', sortable: false},
                        {text: this.$t('message.sections.lookups.valutes.headers.sign'), value: 'sign', align: 'left', sortable: false},
                        {text: this.$t('message.sections.lookups.valutes.headers.created'), value: 'created_at', sortable: false,},
                        {text: '', align: 'right', value: 'action', sortable: false},
                    ],
                    sectionTitle: this.$i18n.t('message.sections.lookups.valutes.section_title'),
                    newItemTitle: this.$i18n.t('message.sections.lookups.valutes.btn_new_title'),
                    formTitleNew: this.$i18n.t('message.sections.lookups.valutes.form.form_title_new'),
                    formTitleEdit: this.$i18n.t('message.sections.lookups.valutes.form.form_title_edit'),
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

            }
        },
        data: function() {
            return {

            }
        },
        methods: {},
        mounted() {}
    }
</script>

<style scoped>

</style>