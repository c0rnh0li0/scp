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
                        prepend-icon="mdi-format-list-bulleted-square"
                        v-model="editedItem.parent_id"
                        ref="category"
                        :value="editedItem.parent_id"
                        :items="$store.state.lookups.categories"
                        item-text="name"
                        item-value="id"
                        label="Parent"
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
                    parent_id: 0
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