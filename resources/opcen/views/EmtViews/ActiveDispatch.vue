<template>
    <div class="flex flex-col md:flex-row h-auto md:h-[70vh] border border-gray-200">
      <!-- Dispatch Information -->
      <div class="w-full md:w-1/3 overflow-y-auto">
          <div v-if="loadingActiveDispatch" class="w-full h-20">
            <Loading />
          </div>
          <template v-else>
            <div v-if="!activeDispatch" class="w-full flex justify-center items-center h-[60svh]">
              <div class="text-2xl font-bold italic text-gray-200">No Active Dispatch</div>
            </div>
            <div v-else class="p-4">
              <!-- Action button -->
              <div class="p-4 flex w-full items-center justify-center">
                <button
                  @click="confirmStatusUpdate()"
                  :disabled="processingDispatch"
                  class="rounded-full h-32 w-32 p-8 font-medium text-md text-white px-5 py-2.5 border-2 border-gray-600 shadow text-center bg-gradient-to-r from-red-900 to-orange-500 hover:bg-primary-700 focus:ring-primary-800 hover:border-yellow-200 hover:bg-gradient-to-l"
                >
                  <template v-if="processingDispatch">
                    <Loading />
                  </template>
                  <template v-else>
                    {{ dispatchStatusLabel[dispatchStatusIndexValue[dispatchStatusIndex[activeDispatch.dispatch.status] + 1]] }}
                  </template>
                </button>
              </div>
              <!-- Status -->
                <div class="text-md font-bold border-b border-red-500 pb-2 mb-2">Dispatch Status:</div>
                <div class="mb-4 p-2 bg-gray-700 rounded-lg inline-block">{{ activeDispatch.dispatch.statusLabel }}</div>
                <div>
                    <b>Request Date: </b> {{ incident.reported_datetime }}
                    <!-- Patient Info -->
                    <div class="text-md font-bold border-b border-red-500 pb-2 my-2">Patient Info:</div>
                    <div class="text-sm text-gray-200 mb-4">
                        <template v-if="incident.patient_ehr_id">
                            <b>Patient ID:</b> {{ incident.patient_ehr_id }}
                            <br>
                        </template>
                        <b>Requester/Patient Name:</b> {{ incident.patient_last_name + ', ' + incident.patient_first_name + (incident.patient_middle_name ? ' ' + String(incident.patient_middle_name).charAt(0) + '.' : '')}}
                        <br>
                        <b>Requester/Patient Birthdate:</b> {{ incident.patient_birthdate }} <span class="italic" v-if="incident.patient_birthdate_is_fortuitous"> (Fortuitous Date)</span>
                        <br>
                        <b>Chief complaints:</b>
                        <br>
                        {{ incident.chief_complaint }}
                        <br>
                        <b>Remarks:</b>
                        <br>
                        {{ incident.remarks }}
                        <br>
                    </div>
                    <!-- Transport Info -->
                    <div class="text-md font-bold border-b border-red-500 pb-2 mb-2">Transport Info:</div>
                    <div class="text-sm text-gray-200 pb-2">
                        <b>Nature of Operation:</b> {{ incident.natureLabel }}
                        <br>
                        <b>Category:</b> {{ incident.categoryLabel }}
                        <br>
                        <b>Type:</b> {{ incident.vicinityLabel }}
                        <br>
                        <b>Transport From:</b> {{ incident.from_health_facility ? incident.from_health_facility.hf_name : incident.origin?.formatted_address }}
                        <br>
                        <b>Transport To:</b> {{  incident.to_health_facility ? incident.to_health_facility.hf_name : incident.destination?.formatted_address }}
                        <br>
                    </div>
                </div>
            </div>
          </template>
      </div>
      <!-- Dispatch Map -->
      <div class="w-full md:w-3/4">
          <Map :incident="incident" :is-emt="true" />
      </div>
      <notifications position="bottom center" />
  </div>
</template>

<script setup lang="ts">
import Map from '@/components/map/Map.vue';
import { useDispatchStore } from '@/stores/dispatch/dispatchStore';
import { useUserStore } from '@/stores/user/userStore';
import { notify } from '@kyvg/vue3-notification';
import { storeToRefs } from 'pinia';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { dispatchStatusIndex, dispatchStatusIndexValue, dispatchStatusLabel } from '@/models/DispatchModel';
import { createConfirmDialog } from 'vuejs-confirm-dialog'
import ConfirmDialogue from '@/components/ConfirmDialogue.vue';
import Loading from '@/components/Loading.vue';

const dispatchStatus = ref(1);

const completeDispatch = () => {
  notify({
    text: "Dispatch Completed!",
    type: "success"
  });
}

const confirm = createConfirmDialog(ConfirmDialogue as any);

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const dispatchStore = useDispatchStore();
const { loadingActiveDispatch, activeDispatch, processingDispatch } = storeToRefs(dispatchStore);

const incident = computed(() => activeDispatch.value ? activeDispatch.value.dispatch.incident : null);
const ambulance = computed(() => activeDispatch.value ? activeDispatch.value.dispatch.ambulance : null);
const driver = computed(() => activeDispatch.value ? activeDispatch.value.dispatch.driver : null);

let locationUpdateInterval: any = null;

const confirmStatusUpdate = async () => {
  const { isCanceled } = await confirm.reveal()

    if(isCanceled) return;
    
    updateDispatchStatus();
}

const updateDispatchLocation = () => {
  // Get users current location using the browser's geolocation API
    // Check if Geolocation is supported by the browser
    const isSupported = 'navigator' in window && 'geolocation' in navigator
    if (isSupported) {
        // Retrieve the user's current position
        navigator.geolocation.getCurrentPosition((position) => {
          dispatchStore.updateDispatchLocation({
            id: activeDispatch.value.dispatch.id,
            last_recorded_location: JSON.stringify({
              lat: position.coords.latitude,
              lng: position.coords.longitude
            })
          });
        })
    } else {
      console.error('cannot retrieve location');
    }
}

const updateDispatchStatus = () => {
  // Get users current location using the browser's geolocation API
    // Check if Geolocation is supported by the browser
    const isSupported = 'navigator' in window && 'geolocation' in navigator
    if (isSupported) {
        // Retrieve the user's current position
        navigator.geolocation.getCurrentPosition((position) => {
          let nextStatus = dispatchStatusIndexValue[dispatchStatusIndex[activeDispatch.value.dispatch.status] + 1];
          dispatchStore.updateDispatchStatus({
            id: activeDispatch.value.dispatch.id,
            status: nextStatus,
            last_recorded_location: JSON.stringify({
              lat: position.coords.latitude,
              lng: position.coords.longitude
            })
          }, successCallback, errorCallback);
        })
    } else {
      notify({
        type: 'error',
        title: 'Error',
        text: 'Failed to retrieve location coordinates'
      });
    }
}

const successCallback = (message) => {
    notify({
        type: 'success',
        title: 'Success',
        text: message
    });
}

const errorCallback = (error) => {
    console.error(error);
}

const startLocationUpdate = () => {
  clearInterval(locationUpdateInterval);
  locationUpdateInterval = setInterval(() => {
    updateDispatchLocation();
  }, 15000);
}

watch(activeDispatch, (newVal) => {
  if(newVal && !['pending', 'endorsed-patient', 'completed'].includes(newVal.dispatch.status)) {
    startLocationUpdate();
  } else {
    clearInterval(locationUpdateInterval);  
  }
}, {deep: true});

onMounted(() => {
  clearInterval(locationUpdateInterval);
  dispatchStore.loadActiveDispatch();
  if(activeDispatch.value && !['pending', 'endorsed-patient', 'completed'].includes(activeDispatch.value.dispatch.status)) {
    startLocationUpdate();
  }
})

onBeforeUnmount(() => {
  clearInterval(locationUpdateInterval);
})


</script>

<style scoped>

</style>