<template>
    <div>
        <v-container grid-list-xl>
            <v-layout row wrap>
                <!-- google maps autocomplete -->
                <v-flex xs12 sm12 md12 lg12 xl12>
                    <v-col class="align-center justify-space-between" cols="12">
                        <gmap-autocomplete
                                class="introInput"
                                placeholder="Search..."
                                @place_changed="setPlace">
                        </gmap-autocomplete>
                        <v-divider></v-divider>
                    </v-col>
                </v-flex>

                <!-- google map component -->
                <v-flex xs12 sm12 md12 lg12 xl12>
                    <v-col cols="12" id="map-container">
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
        <v-container grid-list-xl>
            <v-layout row wrap>
                <v-flex xs12 sm12 md12 lg12 xl12>
                    <v-col cols="12" align="center" justify="center" v-if="tickets.length == 0">
                        <v-alert type="info" align="center" justify="center" max-width="400">
                            No tickets at this time...
                        </v-alert>
                    </v-col>
                    <v-col cols="12" align="center" justify="center" v-else>
                        <v-card flat class="mb-2">
                            <v-card-title class="title">
                                Your unused tickets
                                <v-card-subtitle>
                                    <span class="caption">(Select an unused ticket to get directions to its location)</span>
                                </v-card-subtitle>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-xl>
                                    <v-layout row wrap>
                                        <v-flex v-for="(ticket, i) in unused_tickets" :key="ticket.id" xs6 sm6 md4 lg2 xl2>
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

            // geolocation tracking data
            gettingLocation: true,
            errorStr: '',

            // directions data
            coords: null,
            destination: null,
            tickets: [],
            unused_tickets: []
        }),
        methods: {
            geolocation : function() {
                if (!("geolocation" in navigator)) {
                    this.errorStr = "Geolocation is not available.";
                    return;
                }

                this.gettingLocation = true
                navigator.geolocation.watchPosition(
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
                    }
                )
            },
            // TODO: remove duplicate routes
            getDirection: function() {
                if (typeof google == 'undefined') return

                var directionsService = new google.maps.DirectionsService
                var directionsDisplay = new google.maps.DirectionsRenderer
                directionsDisplay.setMap(null)
                directionsDisplay.setMap(this.$refs.map.$mapObject)

                //google maps API's direction service
                function calculateAndDisplayRoute(directionsService, directionsDisplay, start, destination) {
                    directionsService.route({
                        origin: start,
                        destination: destination,
                        travelMode: 'WALKING'
                    }, function(response, status) {
                        if (status === 'OK') {
                            console.log('directions', response)
                            directionsDisplay.setDirections(response)
                        } else {
                            window.alert('Directions request failed due to ' + status)
                        }
                    });
                }

                this.coords = this.currentLocation
                this.destination = this.markers[0].position

                calculateAndDisplayRoute(directionsService, directionsDisplay, this.coords, this.destination)
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

                this.getDirection()
            },
            centerMyPosition() {
                this.geolocation()
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

                    })
            },
        },
        mounted() {
            console.log('Map mounted')
            //this.geolocation()
            this.getTickets()
        },
        created() {}
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