<template>
    <div>
        <v-app-bar app color="white" :height="barHeightScrollBased">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" :class="[$vuetify.breakpoint.smAndDown ? 'display-3 mr-3': 'd-none']"></v-app-bar-nav-icon>

            <v-avatar :class="[$vuetify.breakpoint.smAndDown ? 'd-none': 'mr-3']" :size="avatarSizeScrollBased">
                <v-img contain max-height="90%" src="/img/skopje-logo-b.png"></v-img>
            </v-avatar>

            <v-toolbar-title class="font-weight-black headline">
                {{ app_name }}
            </v-toolbar-title>

            <v-spacer :class="[$vuetify.breakpoint.smAndDown ? 'd-none': '']"></v-spacer>
            <div :class="[$vuetify.breakpoint.smAndDown ? 'd-none': '']">
                <v-btn text v-for="item in items" :key="'btn_' + item.title" @click="item.route ? $vuetify.goTo(item.route) && menuClick(item.route) : menuClick(item.click)">
                    {{ item.title }}
                </v-btn>
            </div>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" fixed temporary>
            <v-list-item class="justify-center text-center">
                <v-avatar size="120">
                    <v-img contain src="/img/skopje-logo-b.png"></v-img>
                </v-avatar>
            </v-list-item>

            <v-list nav>
                <v-list-item v-for="item in items" :key="item.title" @click="item.route ? $vuetify.goTo(item.route) && menuClick(item.route) : menuClick(item.click)">
                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-content class="pa-0">
            <section id="hero">
                <v-row no-gutters>
                    <v-img :min-height="'calc(100vh - ' + $vuetify.application.top + 'px)'" src="/img/skopje-1.jpg">
                        <v-theme-provider dark>
                            <v-container fill-height>
                                <v-row align="center" class="white--text mx-auto" justify="center">
                                    <v-col class="white--text text-center" cols="12" tag="h1">
                                        <span class="font-weight-light" :class="[$vuetify.breakpoint.smAndDown ? 'display-1' : 'display-2']">
                                            Welcome to
                                        </span>
                                        <br>
                                        <span :class="[$vuetify.breakpoint.smAndDown ? 'display-3': 'display-4']" class="font-weight-black">
                                            {{ app_name }}
                                        </span>
                                    </v-col>
                                    <v-avatar class="mr-3 mt-3" color="grey lighten-5" size="130">
                                        <v-img contain max-height="90%" src="/img/skopje-logo-b.png"></v-img>
                                    </v-avatar>
                                </v-row>
                            </v-container>
                        </v-theme-provider>
                    </v-img>
                </v-row>
            </section>

            <section id="about-me">
                <div class="py-12"></div>
                <v-container class="text-center">
                    <h2 class="display-2 font-weight-bold mb-3">About us</h2>

                    <v-responsive class="mx-auto mb-8" width="56">
                        <v-divider class="mb-1"></v-divider>
                        <v-divider></v-divider>
                    </v-responsive>

                    <v-responsive class="mx-auto body-2 font-weight-light mb-8" max-width="720">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vitae porttitor ex. Aliquam erat volutpat. Morbi vitae tristique purus. Nunc dapibus sem at feugiat finibus. Etiam euismod sapien diam. Nullam tincidunt maximus quam porta hendrerit. Integer vel tempus nulla. Aenean sed sem facilisis, dignissim sem at, elementum neque. Mauris eu justo at sapien efficitur tempus.
                    </v-responsive>

                    <v-avatar class="elevation-12 mb-12" size="128">
                        <v-img src="https://yt3.ggpht.com/a/AGF-l7_oh0ica8uWSZb_bEtFnih-aScpOlhVEpDJ9A=s900-c-k-c0xffffffff-no-rj-mo"></v-img>
                    </v-avatar>

                    <!-- <v-btn color="grey" href="https://vuetifyjs.com" outlined large>
                        <span class="grey--text text--darken-1 font-weight-bold">
                            Vuetify Documentation
                        </span>
                    </v-btn> -->
                </v-container>
            </section>

            <section id="features" class="grey lighten-3">
                <div class="py-12"></div>
                <v-container class="text-center">
                    <h2 class="display-2 font-weight-bold mb-3">SCP FEATURES</h2>

                    <v-responsive class="mx-auto mb-12" width="56">
                        <v-divider class="mb-1"></v-divider>
                        <v-divider></v-divider>
                    </v-responsive>

                    <v-row>
                        <v-col v-for="({ icon, title, text }, i) in features" :key="i" cols="12" md="4">
                            <v-card class="py-12 px-4" color="grey lighten-5" flat>
                                <v-theme-provider dark>
                                    <div>
                                        <v-avatar color="primary" size="88">
                                            <v-icon large v-text="icon"></v-icon>
                                        </v-avatar>
                                    </div>
                                </v-theme-provider>

                                <v-card-title class="justify-center font-weight-black text-uppercase" v-text="title"></v-card-title>

                                <v-card-text class="subtitle-1" v-text="text"></v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>

                <div class="py-12"></div>
            </section>

            <section id="stats">
                <v-parallax
                        :height="$vuetify.breakpoint.smAndDown ? 700 : 500"
                        src="https://images.unsplash.com/photo-1510915228340-29c85a43dcfe?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80"
                >
                    <v-container fill-height>
                        <v-row class="mx-auto">
                            <v-col
                                    v-for="[value, title] of stats"
                                    :key="title"
                                    cols="12"
                                    md="3"
                            >
                                <div class="text-center">
                                    <div
                                            class="display-3 font-weight-black mb-4"
                                            v-text="value"
                                    ></div>

                                    <div
                                            class="title font-weight-regular text-uppercase"
                                            v-text="title"
                                    ></div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-parallax>
            </section>

            <section id="blog">
                <div class="py-12"></div>

                <v-container>
                    <h2 class="display-2 font-weight-bold mb-3 text-uppercase text-center">Blog</h2>

                    <v-responsive
                            class="mx-auto mb-12"
                            width="56"
                    >
                        <v-divider class="mb-1"></v-divider>

                        <v-divider></v-divider>
                    </v-responsive>

                    <v-row>
                        <v-col
                                v-for="({ src, text, title }, i) in articles"
                                :key="i"
                                cols="12"
                                md="4"
                        >
                            <v-img
                                    :src="src"
                                    class="mb-4"
                                    height="275"
                                    max-width="100%"
                            ></v-img>

                            <h3
                                    class="font-weight-black mb-4 text-uppercase"
                                    v-text="title"
                            ></h3>

                            <div
                                    class="title font-weight-light mb-5"
                                    v-text="text"
                            ></div>

                            <v-btn
                                    class="ml-n4 font-weight-black"
                                    text
                            >
                                Continue Reading
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-container>

                <div class="py-12"></div>
            </section>

            <v-sheet
                    id="contact"
                    color="#333333"
                    dark
                    tag="section"
                    tile
            >
                <div class="py-12"></div>

                <v-container>
                    <h2 class="display-2 font-weight-bold mb-3 text-uppercase text-center">Contact Me</h2>

                    <v-responsive
                            class="mx-auto mb-12"
                            width="56"
                    >
                        <v-divider class="mb-1"></v-divider>

                        <v-divider></v-divider>
                    </v-responsive>

                    <v-theme-provider light>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                        flat
                                        label="Name*"
                                        solo
                                ></v-text-field>
                            </v-col>

                            <!-- <v-col cols="12">
                                <v-text-field
                                        flat
                                        label="Email*"
                                        solo
                                ></v-text-field>
                            </v-col> -->

                            <v-col cols="12">
                                <v-text-field
                                        flat
                                        label="Subject*"
                                        solo
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-textarea
                                        flat
                                        label="Message*"
                                        solo
                                ></v-textarea>
                            </v-col>

                            <v-col
                                    class="mx-auto"
                                    cols="auto"
                            >
                                <v-btn
                                        color="accent"
                                        x-large
                                >
                                    Submit
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-theme-provider>
                </v-container>

                <div class="py-12"></div>
            </v-sheet>
        </v-content>

        <v-footer class="justify-center" color="#292929" height="100">
            <div class="title font-weight-light grey--text text--lighten-1 text-center">
                &copy; {{ (new Date()).getFullYear() }} — Darko Krstev
            </div>
        </v-footer>

        <login-form :show-login="loginDialog" @close="loginClose" @login="login" :logerrors="loginErrors"></login-form>
        <register-form :show-register="registerDialog" @close="registerClose" @register="register" :regerrors="registerErrors"></register-form>
    </div>
</template>

<script>
    function toJSONString( form ) {
        var obj = {};
        var elements = form.querySelectorAll( "input, select, textarea" );
        for( var i = 0; i < elements.length; ++i ) {
            var element = elements[i];
            var name = element.name;
            var value = element.value;

            if( name ) {
                obj[ name ] = value;
            }
        }

        return JSON.parse(JSON.stringify(obj));
    }

    import LoginForm from './LoginForm'
    import RegisterForm from './RegisterForm'

    export default {
        components: {
            LoginForm,
            RegisterForm
        },
        props: [
            'loginDialog',
            'registerDialog',
            'loginErrors',
            'registerErrors',
        ],
        watch: {
            loginDialog(newVal, oldVal) {
                return newVal
            },
            registerDialog(newVal, oldVal) {
                return newVal
            },
            loginErrors(newVal, oldVal) {
                return newVal
            },
            registerErrors(newVal, oldVal) {
                return newVal
            },
        },
        data: () => ({
            login_url: '',
            register_url: '',
            csrf: '',
            app_name: '',
            site_url: '',
            offsetTop: 0,
            barHeightScrollBased: 110,
            avatarSizeScrollBased: 90,
            drawer: null,

            items: [
                { title: 'Home', route: '#hero' },
                { title: 'Team', route: '#stats' },
                { title: 'Features', route: '#features' },
                { title: 'Sign in', click: 'login' },
                { title: 'Sign up', click: 'register' },
                { title: 'Contact', route: '#contact' },
            ],

            articles: [
                {
                    src: 'https://images.unsplash.com/photo-1423784346385-c1d4dac9893a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80',
                    title: 'Mobile first & Responsive',
                    text: 'Phasellus lorem enim, luctus ut velit eget, convallis egestas eros. Sed ornare ligula eget tortor tempor, quis porta tellus dictum.',
                },
                {
                    src: 'https://images.unsplash.com/photo-1475938476802-32a7e851dad1?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80',
                    title: 'Think outside the box',
                    text: 'Nam ut leo ipsum. Maecenas pretium aliquam feugiat. Aenean vel tempor est, vitae tincidunt risus. Sed sodales vestibulum nibh.',
                },
                {
                    src: 'https://images.unsplash.com/photo-1416339442236-8ceb164046f8?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1892&q=80',
                    title: 'Small changes, big difference',
                    text: 'Vestibulum in dictum velit, in rhoncus nibh. Maecenas neque libero, interdum a dignissim in, aliquet vitae lectus. Phasellus lorem enim, luctus ut velit eget.',
                },
            ],
            features: [
                {
                    icon: 'mdi-account-group-outline',
                    title: 'Vibrant Community',
                    text: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto cupiditate sint possimus quidem atque harum excepturi nemo velit tempora! Enim inventore fuga, qui ipsum eveniet facilis obcaecati corrupti asperiores nam',
                },
                {
                    icon: 'mdi-update',
                    title: 'Frequent Updates',
                    text: 'Sed ut elementum justo. Suspendisse non justo enim. Vestibulum cursus mauris dui, a luctus ex blandit. Lorem ipsum dolor sit amet consectetur adipisicing elit. qui ipsum eveniet facilis obcaecati corrupti consectetur adipisicing elit.',
                },
                {
                    icon: 'mdi-shield-outline',
                    title: 'Long-term Support',
                    text: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto cupiditate sint possimus quidem atque harum excepturi nemo velit tempora! Enim inventore fuga, qui ipsum eveniet facilis obcaecati corrupti asperiores nam',
                },
            ],
            stats: [
                ['24k', 'Visits'],
                ['20k+', 'Tickets sold'],
                ['10k', 'Tickets/mo'],
                ['100k+', 'Total tickets'],
            ],
        }),
        methods: {
            menuClick(route) {
                this.$emit('actions', route)

                this.drawer = false
            },
            login(data) {
                data._token = this.csrf
                this.$emit('login', this.login_url, data)
            },
            loginClose() {
                this.$emit('loginclose')
            },
            register(data) {
                data._token = this.csrf
                this.$emit('register', this.register_url, data)
            },
            registerClose() {
                this.$emit('registerclose')
            },
            animateAppBar(scrollOffset) {
                this.barHeightScrollBased = scrollOffset > 100 ? 70 : 110
                this.avatarSizeScrollBased = scrollOffset > 100 ? 60 : 90
            },
        },
        async created() {
            this.login_url = window.Laravel.loginUrl
            this.register_url = window.Laravel.registerUrl
            this.csrf = window.Laravel.csrf
            this.app_name = window.Laravel.siteName
            this.site_url = window.Laravel.siteUrl

            let that = this
            window.addEventListener('scroll', function(e) {
                that.animateAppBar(window.scrollY)
            });
        },
        mounted() {}
    }
</script>
<style scoped>
    ul.main-nav {
        margin: 0;
        padding: 0;
    }
</style>