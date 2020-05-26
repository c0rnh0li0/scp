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
        computed: {
            cities() {
                return {
                    apiUrl: 'cities',
                    apiSaveUrl: 'cities/save',
                    apiDeleteUrl: 'cities/delete/',

                    // data table options
                    form: Form,
                    headers: [
                        {text: this.$t('message.sections.lookups.cities.headers.name'), value: 'name', align: 'left', sortable: false},
                        {text: this.$t('message.sections.lookups.cities.headers.country'), value: 'country.name', sortable: false,},
                        {text: this.$t('message.sections.lookups.cities.headers.created'), value: 'created_at', sortable: false,},
                        {text: '', align: 'right', value: 'action', sortable: false},
                    ],
                    sectionTitle: this.$i18n.t('message.sections.lookups.cities.section_title'),
                    newItemTitle: this.$i18n.t('message.sections.lookups.cities.btn_new_title'),
                    formTitleNew: this.$i18n.t('message.sections.lookups.cities.form.form_title_new'),
                    formTitleEdit: this.$i18n.t('message.sections.lookups.cities.form.form_title_edit'),
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