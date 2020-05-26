<template>
    <div style="width: 60px;" class="mt-2 mr-2">
        <v-select
                class="custom"
                :items="languages"
                dense
                item-text="locale"
                item-value="locale"
                v-model="$i18n.locale"
                value="defaultLanguage"
                @change="defaultLanguageChanged"
        ></v-select>
    </div>
</template>

<script>
    export default {
        name: "LanguageSelector",
        data () {
            return {
                defaultLanguage: 'mk',
                languages: []
            }
        },
        methods: {
            getLanguages() {
                axios.get('/api/languages')
                    .then(response => {
                        this.languages = response.data.data
                        this.defaultLanguage = this.languages.find(l => l.id = this.$store.state.settings.default_language).locale
                    })
                    .catch(err => {
                        console.log('Error fetching languages')
                    })
            },
            defaultLanguageChanged(e) {
                this.$vuetify.lang.current = e
                this.$root.$i18n.locale = e
                this.$locale.change(e)
            }
        },
        mounted() {
            if (this.languages.length == 0)
                this.getLanguages()
        }
    }
</script>

<style scoped>

</style>