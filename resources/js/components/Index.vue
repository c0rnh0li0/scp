<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" color="grey lighten-5" app>
            <v-list nav>
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img :src="avatar"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title class="title">{{ username }}</v-list-item-title>
                        <v-list-item-subtitle>{{ email }}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <template v-for="(item, i) in items">
                    <v-row v-if="item.heading" :key="i" align="center">
                        <v-col cols="6">
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-col>
                        <v-col cols="6" class="text-right">
                            <v-btn small text>edit</v-btn>
                        </v-col>
                    </v-row>
                    <v-divider v-else-if="item.divider" :key="i" dark class="my-4" />
                    <v-list-group v-else-if="item.items" :key="i" v-model="item.active" :prepend-icon="item.icon" no-action>
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title v-text="$t(item.text)"></v-list-item-title>
                            </v-list-item-content>
                        </template>

                        <v-list-item v-for="subItem in item.items" :key="subItem.text" :to="subItem.route">
                            <v-list-item-content>
                                <v-list-item-title v-text="$t(subItem.text)"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                    <v-list-item v-else :key="i" :to="item.route" >
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ $t(item.text) }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>

                <v-list-item :key="999" @click.stop="logout" >
                    <v-list-item-action>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="red--text darken-4">
                            {{ $t('message.menus.admin.menu.items.logout') }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <form id="logout-form" method="POST" style="display: none;">
                    <input type="hidden" id="_token" name="_token" value="" />
                </form>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app color="light-blue darken-4" dark>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title>Skopje City Pass</v-toolbar-title>
            <v-spacer></v-spacer>
            <language-selector></language-selector>
            <div v-for="(qitem, j) in quick_items" :key="j">
                <v-btn icon :to="qitem.route">
                    <v-icon>{{ qitem.icon }}</v-icon>
                </v-btn>
            </div>
        </v-app-bar>

        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
        <!-- <v-footer color="blue-grey darken-4" app>
            <span class="white--text">&copy; 2020</span>
        </v-footer> -->
    </v-app>
</template>

<script>
    import LanguageSelector from './LanguageSelector'

    export default {
        components: {
            LanguageSelector
        },
        props: {
            source: String,
        },
        watch: {
            picture(newVal, oldVal) {
                this.avatar = this.$store.state.avatars_path + newVal
                return newVal
            },
        },
        computed: {
            session() {
                return this.$store.state.session
            },
            items() {
                let menu_items = this.$store.state.menu

                // for previewing profile from business scope only
                if (menu_items.length) {
                    if (this.$store.state.type == this.$store.state.types.type_3)
                        menu_items.find(m => m.name == 'business_profile').route += this.$store.state.id + '/true'

                    // display the dashboard for all upon login
                    if (this.$root.$data.isFreshLogin) {
                        this.$router.push(this.$store.state.endpoint + '/dashboard')
                        this.$root.$data.isFreshLogin = false
                    }
                }

                return menu_items
            },
            quick_items() {
                return this.$store.state.quick
            },
            username() {
                return this.$store.state.username
            },
            email() {
                return this.$store.state.email
            },
            picture() {
                if (this.$store.state.picture)
                    this.avatar = this.$store.state.avatars_path + this.$store.state.picture

                return this.$store.state.picture
            },
        },
        data: () => ({
            drawer: null,
            item: 0,
            avatar: ''
        }),
        methods: {
            logout () {
                this.$emit('logout')
            }
        },
        async beforeCreate() {},
        created() {},
        mounted() {
            $('.v-content').removeAttr('style')
        }
    }
</script>
<style scoped>
    .v-content {
        padding: 0px !important;
    }
</style>