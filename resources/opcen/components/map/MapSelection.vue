<template>
    <div>
        <!-- input search box for google places autocomplete -->
        <div class="search-box relative z-0 w-full mb-5 group">
            <GMapAutocomplete
                placeholder=""
                @place_changed="setPlace($event, 'from')"
                id="floating-transport-from"
                :options="autoCompleteOptions"
                class="block mb-2 py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer">
            </GMapAutocomplete>
            <label for="floating-transport-from"
                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
            >Transport from</label>
        </div>
        <div class="search-box relative z-0 w-full mb-5 group">
            <GMapAutocomplete
                placeholder=""
                @place_changed="setPlace($event, 'to')"
                id="floating-transport-to"
                :options="autoCompleteOptions"
                class="block mb-2 py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer">
            </GMapAutocomplete>
            <label for="floating-transport-to"
                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                >Transport to</label>
        </div>

        <!-- rendering the map on the page -->
        <Transition>
            <GMapMap
            ref="map"
            :center="coords" :zoom="15"
            map-type-id="roadmap"
            style="width: 100%; height: 20rem"
            class="mb-4"
            >

            <!-- Marker to display the searched location -->
            <GMapMarker :key="markerDetails.id" :position="markerDetails.position" :clickable="true" :draggable="false"
                @click="openMarker(markerDetails.id)">
                <!-- InfoWindow to display the searched location details -->
                <GMapInfoWindow v-if="locationDetails.address != ''" :closeclick="true" @closeclick="openMarker(null)"
                    :opened="openedMarkerID === markerDetails.id" :options="{
                        pixelOffset: {
                            width: 10,
                            height: 0
                        },
                        maxWidth: 320,
                        maxHeight: 320
                    }">
                    <div class="location-details">
                        <h3>Location Details</h3>
                        <p>Address: {{ locationDetails.address }}</p>
                        <p>
                            URL: <a :href="locationDetails.url" target="_blank"> {{ locationDetails.url }}</a>
                        </p>
                    </div>
                </GMapInfoWindow>

            </GMapMarker>
        </GMapMap>
        </Transition>
        
    </div>
</template>
  
  
<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue';

const showMapOnFocus = ref(false);

// Autocomplete options
const autoCompleteOptions = {
    componentRestrictions: {country: "ph"}
}

// Location variables
const transportFrom = ref<google.maps.Map|null>(null);
const transportTo = ref<google.maps.Map|null>(null);

// Setting the default coordinates
const coords = ref({ lat: 51.5072, lng: 0.1276 })
// Marker Details
const markerDetails = ref({
    id: 1,
    position: coords.value
})
const openedMarkerID = ref(null)

// Places Details
const locationDetails = ref({
    address: '',
    url: ''
})

const map = ref<any>(null);

// Get users current location using the browser's geolocation API
const getUserLocation = () => {
    // Check if Geolocation is supported by the browser
    const isSupported = 'navigator' in window && 'geolocation' in navigator
    if (isSupported) {
        // Retrieve the user's current position
        navigator.geolocation.getCurrentPosition((position) => {
            coords.value.lat = position.coords.latitude
            coords.value.lng = position.coords.longitude
        })
    }
}

// Set the location based on the place selected
const setPlace = (place, model) => {
    // coords.value.lat = place.geometry.location.lat()
    // coords.value.lng = place.geometry.location.lng()

    // Update the location details
    locationDetails.value.address = place.formatted_address
    locationDetails.value.url = place.url

    // update the location variables
    if (model === 'from') {
        transportFrom.value = place;
    } else {
        transportTo.value = place;
    }
    setPath();
}

const setPath = () => {
    path.value = [];
    if (transportFrom.value && transportTo.value) {
        path.value = [
            {lat: transportFrom.value?.geometry.location.lat(), lng: transportFrom.value?.geometry.location.lng()},
            {lat: transportTo.value?.geometry.location.lat(), lng: transportTo.value?.geometry.location.lng()}
        ]
        setDirection();
    }
}

const directionsService = computed<any>(() => new google.maps.DirectionsService ?? null);
const directionsDisplay = computed<any>(() => new google.maps.DirectionsRenderer ?? null);

const setDirection = () => {
    
    calculateAndDisplayRoute(path.value[0], path.value[1]);
}

//google maps API's direction service
const calculateAndDisplayRoute = (start, destination) => {
    directionsDisplay.value.setMap(null);
    directionsService.value.route({
        origin: start,
        destination: destination,
        travelMode: 'DRIVING'
    }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.value.setMap(map.value!.$mapObject);
            directionsDisplay.value.setDirections(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}

// Open the marker info window
const openMarker = (id) => {
    openedMarkerID.value = id
}

interface PathPoint {
    lat: number;
    lng: number;
}

const path = ref<PathPoint[]>([]);

onMounted(() => {
    getUserLocation()
})
</script>
<style scoped>
.location-details {
    color: black;
    font-size: 12px;
    font-weight: 500;
}
</style>