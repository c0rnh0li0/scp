<template>
    <div>
        <crud-index :crud-data="genders"></crud-index>
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
            genders() {
                return {
                    apiUrl: 'genders',
                    apiSaveUrl: 'genders/save',
                    apiDeleteUrl: 'genders/delete/',

                    // data table options
                    form: Form,
                    headers: [
                        {text: this.$t('message.sections.lookups.genders.headers.name'), value: 'name', align: 'left', sortable: false},
                        {text: this.$t('message.sections.lookups.genders.headers.created'), value: 'created_at', sortable: false,},
                        {text: '', align: 'right', value: 'action', sortable: false},
                    ],
                    sectionTitle: this.$i18n.t('message.sections.lookups.genders.section_title'),
                    newItemTitle: this.$i18n.t('message.sections.lookups.genders.btn_new_title'),
                    formTitleNew: this.$i18n.t('message.sections.lookups.genders.form.form_title_new'),
                    formTitleEdit: this.$i18n.t('message.sections.lookups.genders.form.form_title_edit'),
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