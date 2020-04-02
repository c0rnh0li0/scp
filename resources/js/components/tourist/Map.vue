<template>
    <div>
        <v-container grid-list-xl class="ma-0 pa-0">
            <v-layout row wrap class="ma-0 pa-0">
                <!-- google map component -->
                <v-flex xs12 sm12 md12 lg12 xl12 class="ma-0 pa-0">
                    <v-col cols="12" id="map-container" class="ma-0 pa-0">
                        <gmap-map
                                ref="map"
                                :center="{lat:currentLocation.lat, lng:currentLocation.lng}"
                                :zoom="17"
                                map-type-id="satellite"
                                style="width: 100%; height: 500px;"
                                :options="{
                                   zoomControl: true,
                                   mapTypeControl: true,
                                   scaleControl: false,
                                   scrollwheel: true,
                                   streetViewControl: false,
                                   rotateControl: false,
                                   fullscreenControl: true,
                                   disableDefaultUi: true,
                                   gestureHandling: 'auto'
                                }">
                            <gmap-marker
                                    :position="currentLocation"
                                    :clickable="false"
                                    :draggable="false"
                                    :icon="currentLocationIcon"
                            />
                            <gmap-marker
                                    :key="index"
                                    v-for="(m, index) in markers"
                                    :position="m.position"
                                    :clickable="true"
                                    :draggable="false"
                                    @click="center=m.position"
                            />
                        </gmap-map>
                    </v-col>
                    <v-col cols="12" id="controls-container">
                        <v-select
                                v-model="selected_travel_mode"
                                :items="map_travel_modes"
                                @change="changeTravelMode"
                                label="Travel mode"
                        ></v-select>
                    </v-col>
                </v-flex>

                <v-btn bottom
                       fab
                       color="primary"
                       fixed
                       right
                       @click="centerMyPosition">
                    <v-icon light>mdi-crosshairs-gps</v-icon>
                </v-btn>
            </v-layout>
        </v-container>
        <v-container grid-list-xl class="ma-0 pa-0">
            <v-layout row wrap class="ma-0 pa-0">
                <v-flex xs12 sm12 md12 lg12 xl12 class="ma-0 pa-0">
                    <v-col cols="12" align="center" justify="center" v-if="tickets_loaded && tickets.length == 0">
                        <v-alert type="info" align="center" justify="center" max-width="400">
                            No tickets at this time...
                        </v-alert>
                    </v-col>
                    <v-col cols="12" align="center" justify="center" class="ma-0 pa-0" v-else>
                        <v-card flat class="mb-2">
                            <v-card-title class="title">
                                Your unused tickets
                                <v-card-subtitle>
                                    <span class="caption">(Select an unused ticket to get directions to its location)</span>
                                </v-card-subtitle>
                            </v-card-title>
                            <v-card-text class="ma-0 pa-0">
                                <v-container grid-list-xl class="ma-0 pa-0">
                                    <v-layout row wrap class="ma-0 pa-0">
                                        <v-flex v-for="(ticket, i) in unused_tickets" :key="ticket.id" xs6 sm6 md4 lg2 xl2 class="ma-0 pa-0">
                                            <ticket-location :ticket="ticket" @showLocationDirections="showLocationDirections" />
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import currentLocationIcon from "./assets/gmap-current-loc.png";
    import TicketLocation from "./custom/TicketLocation"

    export default {
        name: "Map",
        components: {
            TicketLocation
        },
        data: () => ({
            currentLocationIcon,
            currentLocation: {
                lat: 41.99592912486613,
                lng: 21.431462642771884
            },
            defaultCenter: {
                lat: 41.99592912486613,
                lng: 21.431462642771884
            },
            markers: [{
                position: {
                    lat: 41.99592912486613,
                    lng: 21.431462642771884
                }
            }],

            directionsService: null,
            directionsDisplay: null,
            map_travel_modes: ['WALKING', 'DRIVING', 'BICYCLING', 'TRANSIT'],
            selected_travel_mode: 'WALKING',
            remove_previous_routes: true,
            position_watcher: null,

            // geolocation tracking data
            gettingLocation: true,
            errorStr: '',

            // directions data
            coords: null,
            destination: null,
            tickets: [],
            unused_tickets: [],
            tickets_loaded: false
        }),
        methods: {
            currentPosition: function() {
                if (!("geolocation" in navigator)) {
                    this.errorStr = "Geolocation is not available.";
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    pos => {
                        this.gettingLocation = false

                        this.currentLocation = {
                            lat: pos.coords.latitude,
                            lng: pos.coords.longitude
                        }
                    },
                    err => {
                        this.gettingLocation = false
                        this.errorStr = err.message
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                )
            },
            geolocation : function() {
                if (!("geolocation" in navigator)) {
                    this.errorStr = "Geolocation is not available.";
                    return;
                }

                console.log('watching position')
                this.gettingLocation = true

                if (this.position_watcher) {
                    navigator.geolocation.clearWatch(this.position_watcher)
                    this.position_watcher = null
                }

                this.position_watcher = navigator.geolocation.watchPosition(
                    pos => {
                        console.log('watching position response', pos.coords)

                        this.gettingLocation = false

                        this.currentLocation = {
                            lat: pos.coords.latitude,
                            lng: pos.coords.longitude
                        }

                        this.remove_previous_routes = false
                    },
                    err => {
                        this.gettingLocation = false
                        this.errorStr = err.message
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                )
            },
            getDirection: function() {
                if (typeof google == 'undefined') return

                if (!this.directionsService)
                    this.directionsService = new google.maps.DirectionsService

                this.clearPreviousDirections()
                this.directionsDisplay = new google.maps.DirectionsRenderer
                this.directionsDisplay.setMap(this.$refs.map.$mapObject)

                this.coords = this.currentLocation
                this.destination = this.markers[0].position

                this.calculateAndDisplayRoute(this.directionsService, this.directionsDisplay, this.coords, this.destination, this.selected_travel_mode)
            },
            clearPreviousDirections() {
                if (this.directionsDisplay != null && this.remove_previous_routes) {
                    this.directionsDisplay.setMap(null)
                    this.directionsDisplay.setPanel(null)
                    this.directionsDisplay.set('directions', null);
                    this.directionsDisplay = null
                }
            },
            //google maps API's direction service
            calculateAndDisplayRoute(directionsService, directionsDisplay, start, destination, travelMode) {
                let that = this
                directionsService.route({
                    origin: start,
                    destination: destination,
                    travelMode: travelMode
                }, function(response, status) {
                    if (status === 'OK') {
                        console.log('directions', response)
                        directionsDisplay.setDirections(response)

                        // start tourist location watching
                        that.geolocation()
                    } else {
                        console.log('Directions request failed due to ' + status)
                    }
                })
            },
            setPlace(place) {
                this.defaultCenter = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                }

                this.usePlace(place)
            },
            usePlace(place) {
                this.markers = []
                this.markers.push({
                    position: {
                        lat: place.geometry.location.lat(),
                        lng: place.geometry.location.lng(),
                    }
                })
            },
            showLocationDirections(ticket) {
                console.log(ticket)

                this.markers = []
                this.markers.push({
                    position: {
                        lat: parseFloat(ticket.offer.owner_details.location.latitude),
                        lng: parseFloat(ticket.offer.owner_details.location.longitude)
                    }
                })
                this.remove_previous_routes = true
                this.getDirection()

            },
            centerMyPosition() {
                this.currentPosition()
            },
            changeTravelMode() {
                this.remove_previous_routes = true
                this.getDirection()
            },
            getTickets() {
                let that = this
                axios.get('/api/tickets/')
                    .then(response => {
                        that.tickets = response.data.data
                        that.unused_tickets = that.tickets.filter(o => o.used == 0)
                    })
                    .catch(error => {
                        console.log('error fetching tickets')
                    })
                    .then(() => {
                        that.tickets_loaded = true
                    })
            },
        },
        created() {
            this.getTickets()
        },
        beforeDestroy() {
            if (this.position_watcher) {
                navigator.geolocation.clearWatch(this.position_watcher)
                this.position_watcher = null
            }
        }
    }
</script>

<style scoped>
    .introInput {
        -webkit-box-flex: 1;
        flex: 1 1 auto;
        line-height: 20px;
        padding: 8px 0 8px;
        max-width: 100%;
        min-width: 0px;
        width: 100%;
    }
</style>