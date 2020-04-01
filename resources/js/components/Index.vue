<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app>
            <v-list>
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
                    <input type="hidden" id="_token" name="_token" value="" />
                </form>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app color="blue-grey darken-4" dark>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title>Skopje City Pass</v-toolbar-title>
            <v-spacer />
            <div v-for="(qitem, j) in quick_items" :key="j">
                <v-btn icon :to="qitem.route">
                    <v-icon>{{ qitem.icon }}</v-icon>
                </v-btn>
            </div>
        </v-app-bar>

        <v-content>
            <v-container fluid>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-container>
        </v-content>
        <!-- <v-footer color="blue-grey darken-4" app>
            <span class="white--text">&copy; 2020</span>
        </v-footer> -->
    </v-app>
</template>

<script>
    export default {
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
                return this.$store.state.menu
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
                axios.post(window.Laravel.logoutUrl, {})
                    .then((response) => {
                        if (response.data.response.success) {
                            window.localStorage.removeItem('token')
                            window.location.href = response.data.response.goto
                        }
                    })
                    .catch((err) => {
                        console.log('logout', err);
                    });
            }
        },
        mounted() {
            this.avatar = this.$store.state.avatars_path + this.$store.state.picture
        }
    }
</script>
