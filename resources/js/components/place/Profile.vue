<template>
    <v-container fluid>
        <v-row class="mx-2">
            <v-col class="align-center justify-space-between" cols="12">
                <v-row align="center" class="mr-0">
                    <div id="avatar-container">
                        <ImagePicker v-model="images" :max="1" :activeImageUploads="activeImageUploads">
                            <v-flex xs4 md3>
                                <span class="avatar-lbl">Avatar</span>
                                <v-avatar size="40px" class="mx-3">
                                    <img :src="picture" alt="" width="100%" height="100%" />
                                </v-avatar>
                            </v-flex>
                        </ImagePicker>
                    </div>

                    <v-text-field placeholder="Name" v-model="name" />
                </v-row>
            </v-col>
            <v-col cols="6">
                <v-text-field
                        prepend-icon="mdi-mail"
                        placeholder="Email"
                        v-model="email"
                />
            </v-col>
            <v-col cols="6">
                <v-text-field
                        type="tel"
                        prepend-icon="mdi-phone"
                        placeholder="Telephone"
                        v-model="phone"
                />
            </v-col>
            <v-col cols="12">
                <tiptap-vuetify
                        v-model="description"
                        :extensions="extensions"
                        placeholder="Describe your place here…"
                />
            </v-col>
            <v-col cols="12" id="map-container">
                <div id="autocomplete-container">
                    <v-row class="mx-2">
                        <v-col class="align-center justify-space-between" cols="12">
                            <gmap-autocomplete
                                    class="introInput"
                                    placeholder="Search..."
                                    @place_changed="setPlace">
                            </gmap-autocomplete>
                        </v-col>
                    </v-row>
                </div>

                <v-divider></v-divider>
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
                            :draggable="true"
                            @click="center=m.position"
                            @mouseup="markerMoved"
                    />
                </gmap-map>
            </v-col>

            <v-col cols="12">
                <v-text-field
                        prepend-icon="mdi-city"
                        placeholder="Address"
                        v-model="address"
                />
            </v-col>

            <v-col cols="6">
                <v-text-field
                        prepend-icon="mdi-longitude"
                        placeholder="Longitude"
                        v-model="longitude"
                />
            </v-col>
            <v-col cols="6">
                <v-text-field
                        prepend-icon="mdi-latitude"
                        placeholder="Latitude"
                        v-model="latitude"
                />
            </v-col>
        </v-row>
        <v-btn bottom
               color="success"
               dark
               fab
               fixed
               right
               @click="save">
            <v-icon>mdi-content-save</v-icon>
        </v-btn>
    </v-container>
</template>

<script>
    import {
        TiptapVuetify,
        Heading,
        Bold,
        Italic,
        Strike,
        Underline,
        Code,
        Paragraph,
        BulletList,
        OrderedList,
        ListItem,
        Link,
        Blockquote,
        HardBreak,
        HorizontalRule,
        History,
        Image,
        TodoList,
        TodoItem
    } from 'tiptap-vuetify'

    import { ImagePicker, imageUploadingStates } from "@nagoos/vue-image-picker"
    import placeholderImage from "./assets/placeholder-img.jpg";

    export default {
        components: {
            TiptapVuetify,
            ImagePicker,
        },
        watch: {
            images(newImages, oldImages) {
                this.picture = newImages[0]
            },
        },
        computed: {

        },
        data: () => ({
            save: null,
            loading: false,

            address: '',
            longitude: 0.0,
            latitude: 0.0,
            name: '',
            email: '',
            phone: '',
            description: '',
            picture: placeholderImage,

            // declare extensions you want to use
            extensions: [
                History,
                Blockquote,
                Link,
                Underline,
                Strike,
                Italic,
                ListItem, // if you need to use a list (BulletList, OrderedList)
                BulletList,
                OrderedList,
                Image,
                [
                    Heading,
                    {
                        // Options that fall into the tiptap's extension
                        options: {
                            levels: [1, 2, 3, 4]
                        }
                    }
                ],
                Bold,
                Link,
                Code,
                HorizontalRule,
                TodoList,
                [TodoItem, {
                    options: {
                        nested: true
                    }
                }],
                Paragraph,
                HardBreak // line break on Shift + Ctrl + Enter
            ],
            images: [],
            activeImageUploads: {},
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
        }),
        methods: {
            markerMoved(marker) {
                this.longitude = marker.latLng.lng()
                this.latitude = marker.latLng.lat()
            },
            setPlace(place) {
                console.log(place)
                this.defaultCenter = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                }

                this.address = this.getAddress(place.address_components)
                this.longitude = this.defaultCenter.lng
                this.latitude = this.defaultCenter.lat

                this.usePlace(place)
            },
            usePlace(place) {
                this.markers = []
                this.markers.push({
                    position: {
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng(),
                    }
                })
            },
            getAddress(address_components) {
                let addr = address_components.find(o => o.types.indexOf('route') != -1)
                if (addr && addr.long_name)
                    return addr.long_name
            },
            setDefaultData() {
                this.name = this.$store.state.session.user.name
                this.email = this.$store.state.session.user.email
                this.phone = this.$store.state.session.phone
                this.description = this.$store.state.session.description
                this.picture = this.$store.state.session.picture

                this.address = this.$store.state.session.location ? this.$store.state.session.location.address : ''
                this.longitude = this.$store.state.session.location ? this.$store.state.session.location.longitude : 0.0
                this.latitude = this.$store.state.session.location ? this.$store.state.session.location.latitude : 0.0

                this.defaultCenter = {
                    lat: this.latitude,
                    lng: this.longitude
                }

                this.markers.push(this.defaultCenter)
            },
            save() {
                console.log('saving')
            }
        },
        mounted() {
            console.log('Profile Component mounted.')
            this.setDefaultData()
        },
        created() {
            setTimeout(function () {
                var avatar_cotnainer = document.getElementById("avatar-container");

                var btn_container = avatar_cotnainer.getElementsByClassName("justify-start");
                btn_container[0].parentNode.removeChild(btn_container[0]);

                var txt_container = avatar_cotnainer.getElementsByClassName("layout");
                txt_container[1].parentNode.removeChild(txt_container[1]);
            }, 500)
            this.setDefaultData()
        }
    }
</script>

<style scoped>
    .avatar-lbl {
        color: rgba(0, 0, 0, 0.6);
    }

    .introInput {
        -webkit-box-flex: 1;
        flex: 1 1 auto;
        line-height: 20px;
        padding: 8px 0 8px;
        max-width: 100%;
        min-width: 0px;
        width: 100%;
    }

    #autocomplete-container button {

    }
</style>