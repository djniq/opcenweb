<template>
  <div class="w-full h-full">
    <!-- Date range picker -->
    <div class="flex items-center justify-center pt-4 mb-4">
      <div class="w-auto pr-2 font-bold text-white">
        Select Range:
      </div>
      <div class="w-auto">
        <VueDatePicker
          v-model="dateRange"
          :range="true"
          :multi-calendars="true"
        />
      </div>
    </div>
    <!-- Transport Request Table -->
    <div class="overflow-y-auto block max-h-[64svh]">
      <table class="w-full">
        <thead class="bg-white sticky top-0">
          <tr>
            <th scope="col" class="columns-1 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              ID
            </th>
            <th scope="col" class="columns-1 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Request Date
            </th>
            <th scope="col" class="columns-1 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Patient Info
            </th>
            <th scope="col" class="columns-1 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Transport Info
            </th>
            <th scope="col" class="columns-1 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Dispatch Info
            </th>
            <th scope="col" class="columns-1 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Status
            </th>
            <th scope="col" class="columns-1 border border-gray-500 w-10 text-md font-medium text-gray-900 p-2 text-left">
              Action
            </th>
          </tr>
        </thead>
        <tbody class="overflow-y-auto">
          <template v-if="loadingIncidents">
            <tr class="border-b border-gray-100" >
              <td colspan="7" class="p-2 border border-gray-100">
                <Loading />
              </td>
              </tr>
          </template>
          <template v-else>
            <tr v-for="incident in incidents" class="border-b border-gray-100">
              <td class="p-2 border border-gray-100">{{ incident.id }}</td>
              <td class="p-2 border border-gray-100">
                {{ incident.reportedDatetime }}
              </td>
              <td class="p-2 border border-gray-100">
                <!-- Patient Info -->
                <div class="text-sm text-gray-200 mb-4">
                  <template v-if="incident.patientEhrId">
                    <b>Patient ID:</b> {{ incident.patientEhrId }}
                    <br>
                  </template>
                  <b>Requester/Patient Name:</b> {{ incident.patientLastName + ', ' + incident.patientFirstName + (incident.patientMiddleName ? ' ' + String(incident.patientMiddleName).charAt(0) + '.' : '')}}
                  <br>
                  <b>Requester/Patient Birthdate:</b> {{ incident.patientBirthdate }} <span class="italic" v-if="incident.patientBirthdateIsFortuitous">(Fortuitous Date)</span>
                  <br>
                  <b>Chief complaints:</b>
                  <br>
                  {{ incident.chiefComplaint }}
                  <br>
                  <b>Remarks:</b>
                  <br>
                  {{ incident.remarks }}
                  <br>
                </div>
              </td>
              <td class="p-2 border border-gray-100">
                <div class="text-sm text-gray-200 pb-2">
                  <b>Health Facility:</b> {{ incident.health_facility.hf_name }}
                  <br>
                  <b>Nature Of Operation:</b> {{ incident.natureLabel }}
                  <br>
                  <b>Category:</b> {{ incident.categoryLabel }}
                  <br>
                  <b>Type:</b> {{ incident.vicinityLabel }}
                  <br>
                  <b>Transport From:</b> {{ incident.from_health_facility ? incident.from_health_facility.hf_name : incident.origin?.formatted_address }}
                  <br>
                  <b>Transport To:</b> {{  incident.to_health_facility ? incident.to_health_facility.hf_name : incident.destination?.formatted_address }}
                </div>
              </td>
              <td class="p-2 border border-gray-100">
                <template v-if="incident.dispatch">
                  <div class="flex justify-center">
                      <div class="border border-gray-200 p-2 rounded-full">{{ incident.dispatch.statusLabel }}</div>
                    </div>
                  <div>
                    <div><b>ID:</b> {{ incident.dispatch.id }}</div>
                    <div><b>Ambulance:</b> {{ incident.dispatch.ambulance.amb_plate_no }}</div>
                    <div><b>Driver:</b> {{ incident.dispatch.driver.last_name + ', ' + incident.dispatch.driver.first_name + (incident.dispatch.driver.middle_name ? ' ' + incident.dispatch.driver.middle_name.charAt(0) + '.' : '') }}</div>
                    <div v-if="incident.dispatch.activeSquad"><b>Active Squad:</b> {{ incident.dispatch.activeSquad.squad_name }}</div>
                    <div class="flex flex-col">
                      <div class="font-bold">Responders</div>
                      <div v-for="crew in incident.dispatch.crews">
                        {{ crew.user.last_name + ', ' + crew.user.first_name + (crew.user.middle_name ? ' ' + crew.user.middle_name.charAt(0) + '.' : '') }}
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <action-button @click="showCreateDispatchModal(incident)">
                    Dispatch Responders
                  </action-button>{{}}
                </template>
              </td>
              <td class="p-2 border border-gray-100 text-center">
                <div class="mb-4 p-2 bg-gray-700 rounded-lg inline-block">{{ incident.statusLabel }}</div>
              </td>
              <td class="p-1 border border-gray-100">{{}}
                <div class="flex flex-col space-y-2">
                  <button @click="showIncidentDetails(incident)">
                    <font-awesome-icon :icon="['fas', 'map-location']"/> View Incident
                  </button>
                  <button @click="updateRequest(incident)">
                    <font-awesome-icon :icon="['fas', 'edit']"/> Edit
                  </button>
                </div>
                <!-- <button>Send Dispatch</button>
                <button>Cancel Dispatch</button>
                <button>Complete</button> -->
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <responders-modal v-model:open-create-modal="openCreateDispatchModal" :incident="activeIncident"></responders-modal>
    <IncidentDetailsModal v-model:open-create-modal="openIncidentDetailsModal" :incident="activeIncident" />
  </div>
</template>

<script setup lang="ts">
import VueDatePicker from '@vuepic/vue-datepicker';
import { library } from '@fortawesome/fontawesome-svg-core';
import RespondersModal from './RespondersModal.vue';

/* add some free styles */
import { faEdit, faEye, faMapLocation } from '@fortawesome/free-solid-svg-icons'

import '@vuepic/vue-datepicker/dist/main.css';
import { onMounted, ref, watch } from 'vue';
import IncidentDetailsModal from './IncidentDetailsModal.vue';
import { useIncidentStore } from '@/stores/incident/incidentStore';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';
import ActionButton from '../form/ActionButton.vue';

library.add(faEye, faEdit, faMapLocation);

const dateRange = ref(null);

const emit = defineEmits(['open-update-modal']);

const incidentStore = useIncidentStore();
const { incidents, loadingIncidents } = storeToRefs(incidentStore);

const openCreateDispatchModal = ref(false);
const openIncidentDetailsModal = ref(false);

const activeIncident = ref<any>(null);

const showCreateDispatchModal = (incident) => {
  openCreateDispatchModal.value = true;
  activeIncident.value = incident;
}

const showIncidentDetails = (incident) => {
  activeIncident.value = incident;
  openIncidentDetailsModal.value = true;
}

const updateRequest = (incident) => {
    emit('open-update-modal', incident);
}

watch(dateRange, (newVal) => {
  incidentStore.loadIncidents(newVal);
});

onMounted(() => {
  incidentStore.loadIncidents();
})
</script>

<style scoped></style>