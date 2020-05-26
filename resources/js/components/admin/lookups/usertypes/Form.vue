<template>
    <v-layout row wrap class="pa-2">
        <v-flex xs12 sm12 md6 lg6 xl6>
            <v-col cols="12">
                <v-text-field
                        :label="$t('message.sections.lookups.user_types.form.fields.name')"
                        v-model="editedItem.name"
                        :error-messages="errors.name"
                />
            </v-col>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "Form",
        props: {
            editedItem: {
                type: Object,
                required: false,
                default: {
                    name: ''
                }
            },
            errors: {
                type: Array,
                required: false,
                default: []
            },
        }
    }
</script>

<style scoped>

</style>