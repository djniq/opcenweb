<template>
    <modal v-if="openModal" size="large" @close-modal="openModal = false;">
        <template #modalTitle>
            Incident Details
        </template>
        <template #modalBody>
            <div class="flex flex-col md:flex-row h-auto md:h-[70vh] border border-gray-200">
                <!-- Dispatch Information -->
                <div class="w-full md:w-1/3 overflow-y-auto">
                    <div class="p-4">
                        <div>
                            <b>Request Date: </b> {{ incident.reportedDatetime }}
                            <!-- Patient Info -->
                            <div class="text-md font-bold border-b border-red-500 pb-2 my-2">Patient Info:</div>
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
                            <!-- Status -->
                            <div class="text-md font-bold border-b border-red-500 pb-2 mb-2">Status:</div>
                            <div class="mb-4 p-2 bg-gray-700 rounded-lg inline-block">{{ incident.statusLabel }}</div>
                            <!-- Transport Info -->
                            <div class="text-md font-bold border-b border-red-500 pb-2 mb-2">Transport Info:</div>
                            <div class="text-sm text-gray-200 pb-2">
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
                </div>
                <!-- Dispatch Map -->
                <div class="w-full md:w-3/4">
                    <Map :incident="incident" />
                </div>
                <notifications position="bottom center" />
            </div>
        </template>
    </modal>
</template>

<script setup lang="ts">
import Map from '@/components/map/Map.vue';
import Modal from '../Modal.vue';
import { Incident } from '@/models/IncidentModel';
import { notify } from '@kyvg/vue3-notification';
import { PropType, computed, ref } from 'vue';

const props = defineProps({
    openCreateModal: {
        type: Boolean,
        default: false
    },
    incident: {
        type: Object as PropType<Incident>,
        default: null
    }
})

const emit = defineEmits<{
  (e: "update:openCreateModal", value: Boolean): void;
}>();

const openModal = computed<boolean>({
    get() {
        return props.openCreateModal;
    },
    set(newValue) {
        emit("update:openCreateModal", newValue);
    }
})

const dispatchStatus = ref(1);

</script>
