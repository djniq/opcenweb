<template>
      <!-- rendering the map on the page -->
      <Transition>
        <GMapMap
        ref="map"
        :center="isEmt ? userCoords : coords" :zoom="15"
        map-type-id="roadmap"
        style="width: 100%; height: 70vh;"
        class="mb-4"
        >

        <!-- Marker to display the searched location -->
        <GMapMarker v-if="isEmt || emtCoords" :key="markerDetails.id" :position="markerDetails.position" :clickable="true" :draggable="false"
            @click="openMarker(markerDetails.id)">
            <!-- InfoWindow to display the searched location details -->
            <GMapInfoWindow v-if="incident && incident.dispatch && incident.dispatch.ambulance" :closeclick="true" @closeclick="openMarker(null)"
                :opened="openedMarkerID === markerDetails.id" :options="{
                    pixelOffset: {
                        width: 10,
                        height: 0
                    },
                    maxWidth: 320,
                    maxHeight: 320
                }">
                <div class="transport-details text-black">
                    <h3>Ambulance</h3>
                    <p>Plate no.: {{ incident.dispatch.ambulance.amb_plate_no }}</p>
                </div>
            </GMapInfoWindow>

        </GMapMarker>
    </GMapMap>
  </Transition>
</template>

<script setup lang="ts">
import { PropType, computed, onMounted, onBeforeUnmount, ref, watch } from 'vue';
import { useIncidentStore } from '@/stores/incident/incidentStore';
import { storeToRefs } from 'pinia';

const props = defineProps({
    incident: {
        type: Object as PropType<any>,
        default: null
    },
    isEmt: {
        type: Boolean,
        default: false
    }
});

const incidentStore = useIncidentStore();
const { activeIncident } = storeToRefs(incidentStore);

// Location variables
const transportFrom = ref<google.maps.Map|null>(null);
const transportTo = ref<google.maps.Map|null>(null);

// Setting the default coordinates
const emtCoords = computed(() => {
    if (props.incident 
        && activeIncident 
        && activeIncident.value 
        && props.incident.id === activeIncident.value.id 
        && activeIncident.value.dispatch 
        && activeIncident.value.dispatch.last_recorded_location) {
        return JSON.parse(activeIncident.value.dispatch.last_recorded_location);
    } else if (props.incident 
        && props.incident.dispatch 
        && props.incident.dispatch 
        && props.incident 
        && props.incident.dispatch 
        && props.incident.dispatch.last_recorded_location) {
            return JSON.parse(props.incident.dispatch.last_recorded_location)
    }
    return null;
});
const coords = computed(() => emtCoords.value || { lat: 16.59561989811259, lng: 120.31761403830869 })
const userCoords = ref({ lat: 16.59561989811259, lng: 120.31761403830869 })
// Marker Details
const markerDetails = computed(() => {
    return {
        id: 1,
        position: props.isEmt ? userCoords.value : coords.value
    };
})
const openedMarkerID = ref(null)

// Places Details
const locationDetails = ref({
    address: '',
    url: ''
})

const map = ref<any>(null);

interface PathPoint {
    lat: number;
    lng: number;
}

const path = ref<PathPoint[]>([]);

// Open the marker info window
const openMarker = (id) => {
    openedMarkerID.value = id
}

// Get users current location using the browser's geolocation API
const getUserLocation = () => {
    // Check if Geolocation is supported by the browser
    const isSupported = 'navigator' in window && 'geolocation' in navigator
    if (isSupported) {
        // Retrieve the user's current position
        navigator.geolocation.getCurrentPosition((position) => {
            userCoords.value.lat = position.coords.latitude
            userCoords.value.lng = position.coords.longitude
        })
    }
}

const setPath = () => {
    path.value = [];
    if (transportFrom.value && transportTo.value) {
        path.value = [
            {lat: transportFrom.value?.geometry.location.lat, lng: transportFrom.value?.geometry.location.lng},
            {lat: transportTo.value?.geometry.location.lat, lng: transportTo.value?.geometry.location.lng}
        ]
        setDirection();
    }
}

const directionsService = computed<any>(() => typeof google != 'undefined' ? new google.maps.DirectionsService : null);
const directionsDisplay = computed<any>(() => typeof google != 'undefined' ? new google.maps.DirectionsRenderer : null);

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

watch(() => props.incident, (val) => {
    if (val.origin) {
        getUserLocation();
        transportFrom.value = props.incident.origin;
        transportTo.value = props.incident.destination;
        setTimeout(() => {
           setPath();
        }, 1000);
    }
}, {deep: true})

var reloadInterval:any = null;

onMounted(() => {
  getUserLocation();
  if (props.incident) {
    transportFrom.value = props.incident.origin;
    transportTo.value = props.incident.destination;
    setTimeout(() => {
      setPath();
    }, 1000);
    if (!props.isEmt && props.incident.dispatch.status !== 'completed') {
        if (reloadInterval) {
            clearInterval(reloadInterval);
        }
        reloadInterval = setInterval(() => {
            incidentStore.loadIncidentDetails(props.incident.id);
        }, 10000)
    }
  }

  onBeforeUnmount(() => {
    clearInterval(reloadInterval);
  });
});

</script>

<style scoped>

</style>