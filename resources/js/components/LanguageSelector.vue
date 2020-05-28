<template>
    <v-menu offset-y>
        <template v-slot:activator="{ on: menu }">
            <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltip }">
                    <v-btn icon v-on="{ ...tooltip, ...menu }">
                        <img :src="defaultLanguage.flag" v-if="defaultLanguage.flag" size="24" />
                        <v-icon v-else>mdi-earth</v-icon>
                    </v-btn>
                </template>
                <span>Switch Language</span>
            </v-tooltip>
        </template>
        <v-list>
            <v-list-item v-for="language in languages" :key="language.locale" @click="defaultLanguageChanged(language)">
                <v-list-item-avatar tile size="24">
                    <v-img :src="language.flag"></v-img>
                </v-list-item-avatar>
                <v-list-item-title>{{ language.name }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        name: "LanguageSelector",
        computed:
            mapState({
                settings: state => state.settings,
            })
        ,
        watch: {
            settings(newVal, oldVal) {
                if (newVal.default_language && this.languages.length > 0) {
                    this.defaultLanguage = this.languages.find(l => l.id = this.$store.state.settings.default_language)
                }
            },
            defaultLanguage(newVal, oldVal) {
                this.defaultLanguage = newVal
            }
        },
        data () {
            return {
                val: null,
                languages: [],
                defaultLanguage: {}
                //defaultLanguageFlag: this.$store.state.flags_path + lang.locale + '.png'
            }
        },
        methods: {
            getLanguages() {
                axios.get('/api/languages')
                    .then(response => {
                        this.languages = response.data.data

                        this.languages.forEach(lang => {
                            lang.flag = this.$store.state.flags_path + lang.locale + '.png'
                        })
                    })
                    .catch(err => {
                        console.log('Error fetching languages')
                    })
            },
            defaultLanguageChanged(e) {
                this.$vuetify.lang.current = e.locale
                this.$root.$i18n.locale = e.locale
                this.$locale.change(e.locale)
                this.defaultLanguage = e
                this.defaultLanguageFlag = e.flag
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