<template>
    <v-container grid-list-xl class="ma-0 pa-0">
        <v-layout row wrap class="ma-0 pa-0">
            <!-- google map component -->
            <v-flex xs12 sm12 md12 lg6 xl6 class="ma-0 pa-0">
                <v-col cols="12" id="map-container">
                    <gmap-map
                            ref="mapRef"
                            :center="defaultCenter"
                            :zoom="17"
                            map-type-id="satellite"
                            style="width: 100%; height: 500px;"
                            :options="{
                               zoomControl: true,
                               mapTypeControl: false,
                               scaleControl: false,
                               streetViewControl: false,
                               rotateControl: false,
                               fullscreenControl: true,
                               disableDefaultUi: true,
                               gestureHandling: 'auto'
                         }">
                        <gmap-marker
                                :key="index"
                                v-for="(m, index) in markers"
                                :position="m.position"
                                :clickable="true"
                                :draggable="false"
                        />
                    </gmap-map>
                </v-col>
            </v-flex>

            <!-- profile info -->
            <v-flex xs12 sm12 md12 lg6 xl6 class="ma-0 pa-0">
                <v-col class="text-center justify-space-between" cols="12">
                    <v-avatar size="150">
                        <v-img :src="placeholderImage" v-if="placeholderImage" class="avatar-img" aspect-ratio="1"></v-img>
                    </v-avatar>
                </v-col>

                <v-col cols="12">
                    <div v-html="name" class="title text-center"></div>
                </v-col>

                <v-col cols="12">
                    <v-icon>
                        mdi-mail
                    </v-icon>
                    <span class="body-2 ml-2" v-html="email"></span>
                </v-col>

                <v-col cols="12">
                    <v-icon>
                        mdi-phone
                    </v-icon>
                    <span class="body-2 ml-2" v-html="phone"></span>
                </v-col>

                <v-col cols="12">
                    <v-icon>
                        mdi-city
                    </v-icon>
                    <span class="body-2 ml-2" v-html="address"></span>
                </v-col>

                <v-col cols="12">
                    <v-icon>
                        mdi-earth
                    </v-icon>
                    <span class="body-2 ml-2" v-html="website"></span>
                </v-col>

                <v-col cols="12">
                    <v-icon>
                        mdi-lightbulb
                    </v-icon>
                    <span class="body-2" v-html="category"></span> &gt; <span class="body-2" v-html="subcategory"></span>
                </v-col>
            </v-flex>

            <!-- description -->
            <v-flex xs12 sm12 md12 lg12 xl12>
                <span class="body-2 font-weight-bold">About us...</span>
                <p class="body-2 mt-2" v-html="description"></p>
            </v-flex>

            <offers class="mt-6" :profile-scope="profile_scope" :from-dashboard="false" :business_id="$route.params.id"></offers>

        </v-layout>
    </v-container>
</template>

<script>
    import placeholderImage from "./assets/placeholder-img.jpg"
    import Offers from "../tourist/Offers"

    export default {
        name: "Business",
        components: {
            Offers
        },
        data: () => ({
            profile: null,
            city: '',
            address: '',
            longitude: 0.0,
            latitude: 0.0,
            name: '',
            email: '',
            phone: '',
            website: '',
            description: '',
            picture: '',
            category: '',
            subcategory: '',

            placeholderImage,

            defaultCenter: {
                lat: 41.98611587039809,
                lng: 21.446163830382503
            },
            markers: [{
                position: {
                    lat: 41.98611587039809,
                    lng: 21.446163830382503
                }
            }],

            offers: [],
            featured_offers: [],
            other_offers: [],
            profile_scope: true
        }),
        methods: {
            setPageData(data) {
                this.profileData = data

                this.name = data.user.name
                this.email = data.user.email
                this.phone = data.phone
                this.description = data.description
                this.valute = data.valute ? data.valute.id : null
                this.picture = data.picture ? this.$store.state.avatars_path + data.picture : this.placeholderImage
                this.placeholderImage = data.picture ? this.$store.state.avatars_path + data.picture : this.placeholderImage

                this.city = data.location && data.location.city ? data.location.city.name : ''
                this.address = data.location ? data.location.address : ''
                this.longitude = data.location ? data.location.longitude != '' ? parseFloat(data.location.longitude) : 0.0 : 0.0
                this.latitude = data.location ? data.location.latitude != '' ? parseFloat(data.location.latitude) : 0.0 : 0.0
                this.category = data.location && data.location.category ? data.location.category.name : ''
                this.subcategory = data.location && data.location.subcategory ? data.location.subcategory.name : ''

                if (data.location && this.longitude != 0.0 && this.latitude != 0.0) {
                    this.defaultCenter = {
                        lat: this.latitude,
                        lng: this.longitude
                    }
                }

                this.markers.push({
                    position: this.defaultCenter
                })
            },
            getProfile(id) {
                let that = this
                axios.get('/api/profile/' + id)
                    .then(response => {
                        that.profile = response.data.data
                        that.setPageData(response.data.data)
                    })
                    .catch(error => {
                        console.log('error fetching profile')
                    })
                    .then(() => {

                    })
            },
        },
        mounted() {},
        created() {
            this.getProfile(this.$route.params.id)
            this.profile_scope = this.$route.params.scope
        }
    }
    /*<div>User {{  }}</div>*/
</script>

<style scoped>

</style>