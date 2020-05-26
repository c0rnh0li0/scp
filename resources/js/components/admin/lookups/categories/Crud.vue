<template>
    <div>
        <crud-index :crud-data="categories"></crud-index>
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
            categories() {
                return {
                    apiUrl: 'categories',
                    apiSaveUrl: 'categories/save',
                    apiDeleteUrl: 'categories/delete/',

                    // data table options
                    form: Form,
                    headers: [
                        {text: this.$t('message.sections.lookups.categories.headers.name'), value: 'name', align: 'left', sortable: false},
                        {text: this.$t('message.sections.lookups.categories.headers.parent'), value: 'parent.name', sortable: false,},
                        {text: this.$t('message.sections.lookups.categories.headers.created'), value: 'created_at', sortable: false,},
                        {text: '', align: 'right', value: 'action', sortable: false},
                    ],
                    sectionTitle: this.$i18n.t('message.sections.lookups.categories.section_title'),
                    newItemTitle: this.$i18n.t('message.sections.lookups.categories.btn_new_title'),
                    formTitleNew: this.$i18n.t('message.sections.lookups.categories.form.form_title_new'),
                    formTitleEdit: this.$i18n.t('message.sections.lookups.categories.form.form_title_edit'),
                    editedItem: {
                        id: 0,
                        name: '',
                        parent_id: 0,
                        created_at: '',
                        updated_at: ''
                    },
                    defaultItem: {
                        id: 0,
                        name: '',
                        parent_id: 0,
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