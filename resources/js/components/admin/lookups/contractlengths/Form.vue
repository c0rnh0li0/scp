<template>
    <v-layout row wrap class="pa-2">
        <v-flex xs12 sm12 md6 lg6 xl6>
            <v-col cols="12">
                <v-text-field
                        label="Name"
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
                        label="Duration"
                        :error-messages="errors.duration"
                ></v-select>
            </v-col>
        </v-flex>

        <v-flex xs12 sm12 md6 lg6 xl6>
            <v-col cols="12">
                <v-text-field
                        label="Price"
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
                        label="Valute"
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
        data () {
            return {
                durations: [
                    { id: 1, name: 'One month' },
                    { id: 3, name: 'Three months' },
                    { id: 6, name: 'Six months' },
                    { id: 12, name: 'One year' },
                    { id: 24, name: 'Two years' },
                    { id: 36, name: 'Three Years' },
                ],
            }
        }
    }
</script>

<style scoped>

</style>