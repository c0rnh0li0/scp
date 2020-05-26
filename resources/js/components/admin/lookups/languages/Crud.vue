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
        computed: {
            languages() {
                return {
                    apiUrl: 'languages',
                    apiSaveUrl: 'languages/save',
                    apiDeleteUrl: 'languages/delete/',

                    // data table options
                    form: Form,
                    headers: [
                        { text: this.$t('message.sections.lookups.languages.headers.name'), value: 'name', align: 'left', sortable: false },
                        { text: this.$t('message.sections.lookups.languages.headers.locale'), value: 'locale', align: 'left', sortable: false },
                        { text: this.$t('message.sections.lookups.languages.headers.created'), value: 'created_at', sortable: false, },
                        {text: '', align: 'right', value: 'action', sortable: false},
                    ],
                    sectionTitle: this.$i18n.t('message.sections.lookups.languages.section_title'),
                    newItemTitle: this.$i18n.t('message.sections.lookups.languages.btn_new_title'),
                    formTitleNew: this.$i18n.t('message.sections.lookups.languages.form.form_title_new'),
                    formTitleEdit: this.$i18n.t('message.sections.lookups.languages.form.form_title_edit'),
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
            },
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