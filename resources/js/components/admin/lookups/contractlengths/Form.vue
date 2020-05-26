<template>
    <v-layout row wrap class="pa-2">
        <v-flex xs12 sm12 md6 lg6 xl6>
            <v-col cols="12">
                <v-text-field
                        :label="$t('message.sections.lookups.contract_lengths.form.fields.name')"
                        v-model="editedItem.name"
                        :error-messages="errors.name"
                />
            </v-col>
        </v-flex>

        <v-flex xs12 sm12 md6 lg6 xl6>
            <v-col cols="12">
                <v-select
                        v-model="editedItem.duration"
                        :value="editedItem.duration"
                        :items="durations"
                        item-text="name"
                        item-value="id"
                        :label="$t('message.sections.lookups.contract_lengths.form.fields.duration')"
                        :error-messages="errors.duration"
                ></v-select>
            </v-col>
        </v-flex>

        <v-flex xs12 sm12 md6 lg6 xl6>
            <v-col cols="12">
                <v-text-field
                        :label="$t('message.sections.lookups.contract_lengths.form.fields.price')"
                        type="numeric"
                        v-model="editedItem.price"
                        :error-messages="errors.price"
                />
            </v-col>
        </v-flex>

        <v-flex xs12 sm12 md6 lg6 xl6>
            <v-col cols="12">
                <v-select
                        v-model="editedItem.valute_id"
                        :value="editedItem.valute_id"
                        :items="$store.state.lookups.valutes"
                        item-text="name"
                        item-value="id"
                        :label="$t('message.sections.lookups.contract_lengths.form.fields.valute')"
                        :error-messages="errors.valute_id"
                ></v-select>
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
                    name: '',
                    duration: 0,
                    price: 0.0,
                    valute_id: ''
                }
            },
            errors: {
                type: Array,
                required: false,
                default: []
            },
        },
        data: function () {
            return {
                durations: [
                    { id: 1, name: this.$i18n.t('message.sections.lookups.contract_lengths.durations.months_1') },
                    { id: 3, name: this.$i18n.t('message.sections.lookups.contract_lengths.durations.months_3') },
                    { id: 6, name: this.$i18n.t('message.sections.lookups.contract_lengths.durations.months_6') },
                    { id: 12, name: this.$i18n.t('message.sections.lookups.contract_lengths.durations.months_12') },
                    { id: 24, name: this.$i18n.t('message.sections.lookups.contract_lengths.durations.months_24') },
                    { id: 36, name: this.$i18n.t('message.sections.lookups.contract_lengths.durations.months_36') },
                ],
            }
        }
    }
</script>

<style scoped>

</style>