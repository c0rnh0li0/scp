<template>
    <v-banner elevation="6" class="green lighten-5 install-banner position-fixed fixed-bottom" :value="installBannerVisible && showOnScrollOffset">
        <v-icon slot="icon" color="success" size="36">
            mdi-download
        </v-icon>
        Do you want to install Skopje City Pass as an application?

        <template v-slot:actions>
            <v-btn text color="warning darken-3" @click="dismissInstall">Dismiss</v-btn>
            <v-btn text color="success darken-1" @click="installApp">Install</v-btn>
        </template>
    </v-banner>
</template>

<script>
    window.deferredPrompt = {}

    window.addEventListener('appinstalled', (evt) => {
        window.localStorage.setItem('_i', true)
    })

    // if the app can be installed emit beforeinstallprompt
    window.addEventListener('beforeinstallprompt', e => {
        e.preventDefault()

        // store install avaliable event
        window.deferredPrompt = e
    })

    // Detects if device is on iOS
    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase()
        return /iphone|ipad|ipod/.test( userAgent )
    }

    // Detects if device is in standalone mode
    const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone)

    export default {
        name: "InstallBanner",
        data: () => ({
            installBannerVisible: false,
            showOnScrollOffset: false
        }),
        methods: {
            installApp() {
                let that = this
                window.deferredPrompt.prompt()
                window.deferredPrompt.userChoice.then(choiceResult => {
                    if (choiceResult.outcome === 'accepted') {
                        window.localStorage.setItem('_i', true)
                        // user accept the prompt
                    } else {
                        console.log('User dismissed the prompt')
                    }
                    window.deferredPrompt = null
                    that.installBannerVisible = false
                });
            },
            dismissInstall() {
                this.installBannerVisible = false
            }
        },
        created() {
            let i = window.localStorage.getItem('_i')

            if (!i || !window.matchMedia('(display-mode: standalone)').matches || (isIos() && !isInStandaloneMode())) {
                this.installBannerVisible = true
            }

            let ticking = false
            let that = this
            window.addEventListener('scroll', function(e) {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        that.showOnScrollOffset = window.scrollY > 200
                        ticking = false;
                    });

                    ticking = true;
                }
            });
        },
        mounted() {}
    }
</script>

<style scoped>
    .install-banner {
        z-index: 99999;
    }
</style>