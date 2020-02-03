<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app>
            <v-list dense>
                <template v-for="(item, i) in items">

                    <v-list-item v-if="item.text" :key="i" align="center">
                        <router-link :to="item.route">
                            <v-list-item-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title class="grey--text">
                                    {{ item.text }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </router-link>
                    </v-list-item>
                    <v-divider v-else-if="item.divider" :key="i" dark class="my-4" />
                </template>

                <!-- <router-link to="/admin" link>
                    <v-list-item>
                        <v-list-item-action>
                            <v-icon>mdi-home</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Home</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </router-link>
                <router-link to="/about" link>
                    <v-list-item>
                        <v-list-item-action>
                            <v-icon>mdi-contact-mail</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Skopje City Pass</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </router-link> -->
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app color="indigo" dark>
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
        <v-footer color="indigo" app>
            <span class="white--text">&copy; 2020</span>
        </v-footer>
    </v-app>
</template>

<script>
    export default {
        props: {
            source: String,
        },
        data: () => ({
            drawer: null,
            items: [
                { icon: 'mdi-home-modern', text: 'Home', route: '/admin' },
                { icon: 'mdi-account', text: 'About', route: '/about' },
            ],
        }),
    }
</script>