<template>
    <div>
        <crud-index :crud-data="countries"></crud-index>
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
            countries() {
                return {
                    apiUrl: 'countries',
                    apiSaveUrl: 'countries/save',
                    apiDeleteUrl: 'countries/delete/',

                    // data table options
                    form: Form,
                    headers: [
                        {text: this.$t('message.sections.lookups.countries.headers.name'), value: 'name', align: 'left', sortable: false},
                        {text: this.$t('message.sections.lookups.countries.headers.code'), value: 'code', sortable: false,},
                        {text: this.$t('message.sections.lookups.countries.headers.created'), value: 'created_at', sortable: false,},
                        {text: '', align: 'right', value: 'action', sortable: false},
                    ],
                    sectionTitle: this.$i18n.t('message.sections.lookups.countries.section_title'),
                    newItemTitle: this.$i18n.t('message.sections.lookups.countries.btn_new_title'),
                    formTitleNew: this.$i18n.t('message.sections.lookups.countries.form.form_title_new'),
                    formTitleEdit: this.$i18n.t('message.sections.lookups.countries.form.form_title_edit'),
                    editedItem: {
                        id: 0,
                        name: '',
                        code: '',
                        created_at: '',
                        updated_at: ''
                    },
                    defaultItem: {
                        id: 0,
                        name: '',
                        code: '',
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
        mounted() {}
    }
</script>

<style scoped>

</style>