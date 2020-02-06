<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app>
            <v-list>
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img src="https://cdn.vuetifyjs.com/images/john.png"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title class="title">John Leider</v-list-item-title>
                        <v-list-item-subtitle>john@vuetifyjs.com</v-list-item-subtitle>
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
                    <v-list-item v-else :key="i" :to="item.route" >
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="blue-grey--text darken-4">
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>

                <v-list-item :key="999" @click.stop="logout" >
                    <v-list-item-action>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="blue-grey--text darken-4">
                            Logout
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <form id="logout-form" method="POST" style="display: none;">
                    <input type="hidden" id="_token" name="_token" value="yM9fsLlf971YvlouAXsK8AJMymhTQ3h7QwNHaxw8" />
                </form>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app color="blue-grey darken-4" dark>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title>Skopje City Pass</v-toolbar-title>
        </v-app-bar>

        <v-content>
            <v-container fluid>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-container>
        </v-content>
        <v-footer color="blue-grey darken-4" app>
            <span class="white--text">&copy; 2020</span>
        </v-footer>
    </v-app>
</template>

<script>
    export default {
        props: {
            source: String,
        },
        mounted() {
            document.getElementById('logout-form').action = window.Laravel.logoutUrl
            document.getElementById('_token').value = window.Laravel.csrf
        },
        data: () => ({
            drawer: null,
            item: 0,
            items: [
                { icon: 'mdi-home-modern', text: 'Home', route: '/admin' },
                { icon: 'mdi-file-document-edit', text: 'Contracts', route: '/admin/contracts' },
                { icon: 'mdi-bank', text: 'Places', route: '/admin/places' },
                { icon: 'mdi-account', text: 'People', route: '/admin/people' },
                { icon: 'mdi-qrcode-scan', text: 'Tickets', route: '/admin/tickets' },
                { divider: true },
                { icon: 'mdi-database', text: 'Lookup data', route: '/admin/lookups' },
                { icon: 'mdi-certificate-outline', text: 'Tokens', route: '/admin/tokens' },
            ],
        }),
        methods: {
            logout () {
                document.getElementById('logout-form').submit()
            }
        }
    }
</script>
