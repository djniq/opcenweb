<template>
    <modal v-if="openModal" @close-modal="openModal = false;">
        <template #modalTitle>
            <span v-if="mode === 'create'">Create New Transport Request</span>
            <span v-else>Update Transport Request</span>
        </template>
        <template #modalBody>
            <div v-if="processingIncident" class="w-full h-20">
                <Loading />
            </div>
            <form v-else class="mx-auto" @submit.prevent="handleSubmit()">
                <div v-if="user?.role === 'superadmin'" class="px-4 mb-4">
                    <div class="text-gray-400 text-sm">
                        Reporting Health Facility
                    </div>
                    <label
                        for="reporting-health-facility-select"
                        class="sr-only"
                    >
                    Reporting Health Facility
                    </label>
                    <template v-if="loadingOptions">
                        <Loading />
                    </template>
                    <select
                        v-else
                        v-model="incidentForm.healthFacilityId"
                        id="reporting-health-facility-select"
                        class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                        required
                    >
                        <option
                            value=""
                            disabled
                            selected
                        >
                            Select Health Facility
                        </option>
                        <option
                            v-for="option in facilityOptions"
                            :value="option.id">
                                {{ option.name }}
                            </option>
                    </select>
                </div>
                <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                    Nature of Operation
                </div>
                <div class="px-4 mb-4">
                    <fieldset>
                        <legend class="sr-only">Nature of Operation</legend>
                        <div class="flex items-center mb-4">
                            <input
                                id="type-option-1"
                                v-model="incidentForm.nature"
                                type="radio"
                                name="natureOfOperation"
                                value="conduction"
                                class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                            >
                            <label for="type-option-1" class="block ms-2 text-sm font-medium text-gray-300">
                                Conduction
                            </label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input
                                id="type-option-2"
                                v-model="incidentForm.nature"
                                type="radio"
                                name="natureOfOperation"
                                value="emergency-dispatch-trauma"
                                class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                            >
                            <label for="type-option-2" class="block ms-2 text-sm font-medium text-gray-300">
                                Emergency Dispatch(Trauma)
                            </label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input
                                id="type-option-3"
                                v-model="incidentForm.nature"
                                type="radio"
                                name="natureOfOperation"
                                value="emergency-dispatch-medical"
                                class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                            >
                            <label for="type-option-3" class="block ms-2 text-sm font-medium text-gray-300">
                                Emergency Dispatch(Medical)
                            </label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input
                                id="type-option-4"
                                v-model="incidentForm.nature"
                                type="radio"
                                name="natureOfOperation"
                                value="deployment"
                                class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                            >
                            <label for="type-option-4" class="block ms-2 text-sm font-medium text-gray-300">
                                Deployment
                            </label>
                        </div>
                    </fieldset>
                </div>
                <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                    Category of Transfer
                </div>
                <div class="px-4 mb-8 space-y-4">
                    <label
                        for="transfer-category-select"
                        class="sr-only"
                    >
                        Category of Transfer
                    </label>
                    <div>
                        <select
                            id="transfer-category-select"
                            ref="category"
                            v-model="incidentForm.category"
                            class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white ring-blue-500 focus:border-blue-500"
                            :class="{
                                'border-red-500 focus:border-red-500 ring-red-500' : errors.category
                            }"
                            placeholder="Select a category"    
                        >
                            <option value="" disabled>Select a Category</option>
                            <option value="step-up">Step up</option>
                            <option value="step-down">Step down</option>
                        </select>
                        <div v-if="errors.category" class="text-red-400 text-sm">{{ errors.category }}</div>    
                    </div>
                    <div>
                        <select
                            id="transfer-area-coverage-select"
                            ref="vicinity"
                            v-model="incidentForm.vicinity"
                            class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white ring-blue-500 focus:border-blue-500"
                            :class="{
                                'border-red-500 focus:border-red-500 ring-red-500' : errors.vicinity
                            }"
                            placeholder="Select area coverage"    
                        >
                            <option value="" disabled>Select Area Coverage</option>
                            <option value="within">Within La Union</option>
                            <option value="outside">Outside La Union</option>
                        </select>
                        <div v-if="errors.vicinity" class="text-red-400 text-sm">{{ errors.vicinity }}</div>
                    </div>
                </div>
                <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                    Journey
                </div>
                <div class="px-4">
                    <!-- Location origin selection -->
                    <div
                        class="flex w-full flex-col mb-4 space-y-2"
                        :class="{
                            'border-b border-red-500': errors.origin
                        }"
                    >
                        <b>Transport From</b>
                        <!-- Radio buttons for selecting the option for transport origin -->
                        <div class="flex">
                            <div class="pr-2 flex items-center">
                                <input
                                    id="from-current"
                                    v-model="transportFromOption"
                                    type="radio"
                                    name="transportFrom"
                                    value="current"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                >
                                <label for="from-current" class="block ms-2 text-sm font-medium text-gray-300">
                                    Current Facility
                                </label>
                            </div>
                            <div class="pr-2 flex items-center">
                                <input
                                    id="from-facility"
                                    v-model="transportFromOption"
                                    type="radio"
                                    name="transportFrom"
                                    value="facility"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                >
                                <label for="from-facility" class="block ms-2 text-sm font-medium text-gray-300">
                                    Select Health Facility
                                </label>
                            </div>
                            <div class="pr-2 flex items-center">
                                <input
                                    id="from-custom"
                                    ref="origin"
                                    v-model="transportFromOption"
                                    type="radio"
                                    name="transportFrom"
                                    value="custom"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                >
                                <label for="from-custom" class="block ms-2 text-sm font-medium text-gray-300">
                                    Search Location
                                </label>
                            </div>
                        </div>
                        <!-- Display the location selection depending on the selected origin option -->
                        <div v-if="transportFromOption && transportFromOption !=='current'" class="w-full">
                            <template v-if="transportFromOption === 'facility'">
                                <div class="text-gray-400 text-sm">
                                    From Health Facility
                                </div>
                                <label
                                    for="from-health-facility-select"
                                    class="sr-only"
                                >
                                    From Health Facility
                                </label>
                                <select
                                    v-model="incidentForm.fromHealthFacilityId"
                                    id="from-health-facility-select"
                                    class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                                    required
                                >
                                    <option
                                        value=""
                                        disabled
                                        selected
                                    >
                                        <template v-if="loadingOptions">
                                            <Loading />
                                        </template>
                                        <template v-else>
                                            Select Health Facility
                                        </template>
                                    </option>
                                    <option
                                        v-for="option in facilityOptions"
                                        :value="option.id">
                                            {{ option.name }}
                                        </option>
                                </select>
                            </template>
                            <template v-if="transportFromOption === 'custom'">
                                <AddressSelection
                                    v-model:form-address="incidentForm.origin"
                                    required
                                />
                            </template>
                        </div>
                        <div v-if="errors.origin" class="text-red-400 text-sm">{{ errors.origin }}</div>
                    </div>
                    <!-- Location destination selection -->
                    <div
                        class="flex w-full flex-col mb-4 space-y-2"
                        :class="{
                            'border-b border-red-500': errors.destination
                        }"
                    >
                        <b>Transport To</b>
                        <!-- Radio buttons for selecting the option for transport destination -->
                        <div class="flex">
                            <div class="pr-2 flex items-center">
                                <input
                                    id="to-current"
                                    v-model="transportToOption"
                                    type="radio"
                                    name="transportTo"
                                    value="current"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                >
                                <label for="to-current" class="block ms-2 text-sm font-medium text-gray-300">
                                    Current Facility
                                </label>
                            </div>
                            <div class="pr-2 flex items-center">
                                <input
                                    id="to-facility"
                                    v-model="transportToOption"
                                    type="radio"
                                    name="transportTo"
                                    value="facility"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                >
                                <label for="to-facility" class="block ms-2 text-sm font-medium text-gray-300">
                                    Select Health Facility
                                </label>
                            </div>
                            <div class="pr-2 flex items-center">
                                <input
                                    id="to-custom"
                                    ref="destination"
                                    v-model="transportToOption"
                                    type="radio"
                                    name="transportTo"
                                    value="custom"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                >
                                <label for="to-custom" class="block ms-2 text-sm font-medium text-gray-300">
                                    Search Location
                                </label>
                            </div>
                        </div>
                        <!-- Display the location selection depending on the selected destination option -->
                        <div v-if="transportToOption && transportToOption !== 'current'" class="w-full">
                            <template v-if="transportToOption === 'facility'">
                                <div class="text-gray-400 text-sm">
                                    To Health Facility
                                </div>
                                <label
                                    for="to-health-facility-select"
                                    class="sr-only"
                                >
                                    To Health Facility
                                </label>
                                <template v-if="loadingOptions">
                                    <Loading />
                                </template>
                                <select
                                    v-else
                                    v-model="incidentForm.toHealthFacilityId"
                                    id="to-health-facility-select"
                                    class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                                    required
                                >
                                    <option
                                        value=""
                                        disabled
                                        selected
                                    >
                                        Select Health Facility
                                    </option>
                                    <option
                                        v-for="option in facilityOptions"
                                        :value="option.id">
                                            {{ option.name }}
                                        </option>
                                </select>
                            </template>
                            <template v-if="transportToOption === 'custom'">
                                <AddressSelection
                                    v-model:form-address="incidentForm.destination"
                                    required
                                />
                            </template>
                        </div>
                    </div>
                    <div v-if="errors.destination" class="text-red-400 text-sm">{{ errors.destination }}</div>
                </div>
                <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                    Patient Information
                </div>
                <div
                    class="flex w-full flex-col mb-4 space-y-2"
                >
                    <b>Select source</b>
                    <!-- Radio buttons for selecting the option for patient record source -->
                    <div class="flex space-x-4">
                        <div class="pr-2 flex items-center">
                            <input
                                id="ehr-source"
                                v-model="patientRecordSourceOption"
                                type="radio"
                                name="patientRecordSource"
                                value="ehr"
                                class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                            >
                            <label for="ehr-source" class="block ms-2 text-sm font-medium text-gray-300">
                                Search EHR
                            </label>
                        </div>
                        <div class="pr-2 flex items-center">
                            <input
                                id="manual-entry"
                                v-model="patientRecordSourceOption"
                                type="radio"
                                name="patientRecordSource"
                                value="manual"
                                class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                            >
                            <label for="manual-entry" class="block ms-2 text-sm font-medium text-gray-300">
                                Manual Entry
                            </label>
                        </div>
                    </div>
                </div>
                <div v-if="patientRecordSourceOption === 'ehr'" class="px-4">
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text"
                            v-model="incidentForm.patientEhrId"
                            name="floating-patient-id" 
                            id="floating-patient-id" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer"
                            placeholder=" "
                        />
                        <label
                            for="floating-patient-id"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Patient ID</label>
                    </div>
                    <div class="w-full">
                        <div class="flex p-2 justify-center">
                            <action-button
                                v-if="!ehrPatient"
                                @click="searchEhrPatient()"
                                :disabled="!incidentForm.patientEhrId"
                            >
                                Search Patient
                            </action-button>
                            <delete-button
                                v-if="incidentForm.patientEhrId && ehrPatient"
                                @click="resetEhrPatient()"
                            >
                                Reset
                            </delete-button>
                        </div>
                        <!-- <div v-if="ehrPatient" class="flex flex-col">
                            <div><b>Patient's Name: </b></div>
                            <div><b> Patient's Birthdate: </b>1970-12-10</div>
                            <div><b> Patient's Address: </b>Address Here</div>
                            <div><b>Chief Complaint: </b></div>
                            <div><b>Remarks: </b></div>
                        </div> -->
                    </div>
                </div>
                <div class="px-4">
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text"
                            v-model="incidentForm.patientFirstName"
                            name="floating-patient-first-name" 
                            id="floating-patient-first-name" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer"
                            placeholder=" "
                            :disabled="patientRecordSourceOption === 'ehr'"
                            required />
                        <label
                            for="floating-patient-first-name"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Patient/Requester First Name</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text" 
                            v-model="incidentForm.patientLastName"
                            name="floating-patient-last-name" 
                            id="floating-patient-last-name" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer"
                            placeholder=" "
                            :disabled="patientRecordSourceOption === 'ehr'"
                            required />
                        <label
                            for="floating-patient-last-name"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Patient/Requester Last Name</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text"
                            v-model="incidentForm.patientMiddleName"
                            name="floating-patient-middle-name" 
                            id="floating-patient-middle-name" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer"
                            placeholder=" "
                            :disabled="patientRecordSourceOption === 'ehr'"
                        />
                        <label
                            for="floating-patient-middle-name"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Patient/Requester Middle Name (Not required)</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group flex">
                        <div class="w-1/2">
                            <label
                                for="patient-birthdate"
                                class="block mb-2 text-sm text-gray-400"
                            >Patient's Date of Birth</label>
                        </div>
                        <div class="w-1/2">
                            <VueDatePicker
                                id="patient-birthdate"
                                v-model="incidentForm.patientBirthdate"
                                model-type="yyyy-MM-dd"
                                :range="false"
                                :enable-time-picker="false"
                                :max-date="new Date()"
                                :teleport="true"
                                format="MMM dd, yyyy"
                                :disabled="patientRecordSourceOption === 'ehr'"
                                required
                            />
                            <div>
                                <input
                                    type="checkbox"
                                    id="checkbox-fortuitous-date"
                                    class="mr-2"
                                    v-model="incidentForm.patientBirthdateIsFortuitous"
                                    :disabled="patientRecordSourceOption === 'ehr'"
                                >
                                <label for="checkbox-fortuitous-date">Fortuitous Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text"
                            v-model="incidentForm.patientAddress"
                            name="floating-patient-address" 
                            id="floating-patient-address" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer"
                            placeholder=" "
                            :disabled="patientRecordSourceOption === 'ehr'"
                            required
                        />
                        <label
                            for="floating-patient-address"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Patient/Requester Address</label>
                    </div>
                    
                    <div class="relative z-0 w-full mb-5 group">                        
                        <label
                            for="chief-complaints"
                            class="block mb-2 text-sm font-medium text-white"
                            >Chief Complaints</label>
                        <textarea
                            id="chief-complaints"
                            v-model="incidentForm.chiefComplaint"
                            rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Patient's chief complaint..."
                            :disabled="patientRecordSourceOption === 'ehr'"
                            required    
                        ></textarea>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <label
                            for="remarks"
                            class="block mb-2 text-sm font-medium text-white"
                            >Remarks</label>
                        <textarea
                            id="remarks"
                            v-model="incidentForm.remarks"
                            rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Remarks..."
                            :disabled="patientRecordSourceOption === 'ehr'"
                        ></textarea>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="self-start">
                        <delete-button
                            v-if="mode === 'update'"
                            @button-action="confirmDelete(incident.id)"
                        >
                            <font-awesome-icon :icon="['fa', 'trash']" /> Delete
                        </delete-button>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <action-button
                            type="submit"
                        >
                            Save
                        </action-button>
                        <button
                            type="button"
                            class="text-white bg-red-500 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                            @click="openModal = false;"
                        >Cancel</button>
                    </div>
                </div>
            </form>
        </template>
    </modal>
</template>

<script setup lang="ts">
import { PropType, computed, onMounted, ref, watch } from 'vue';
import Modal from '../Modal.vue';
import ActionButton from '../form/ActionButton.vue';
import DeleteButton from '../form/DeleteButton.vue';
import { Incident } from '@/models/IncidentModel';
import { useHealthFacilityStore } from '@/stores/healthFacility/healthFacilityStore';
import { storeToRefs } from 'pinia';
import VueDatePicker from '@vuepic/vue-datepicker';
import AddressSelection from '../map/AddressSelection.vue';
import { useIncidentStore } from '@/stores/incident/incidentStore';
import Loading from '../Loading.vue';
import { notify } from '@kyvg/vue3-notification';
import { createConfirmDialog } from 'vuejs-confirm-dialog'
import ConfirmDialogue from '@/components/ConfirmDialogue.vue';
import { useUserStore } from '@/stores/user/userStore';

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

const isUpdateInitialLoad = ref(false);

const emit = defineEmits<{
  (e: "update:openCreateModal", value: Boolean): void;
}>();

const confirm = createConfirmDialog(ConfirmDialogue as any);

const facilityStore = useHealthFacilityStore();
const { facilityOptions, loadingOptions } = storeToRefs(facilityStore);

const incidentStore = useIncidentStore();
const { processingIncident, loadingEhr, ehrPatient } = storeToRefs(incidentStore);

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const patientRecordSourceOption = ref<'ehr'|'manual'>('ehr');

const openModal = computed<boolean>({
    get() {
        return props.openCreateModal;
    },
    set(newValue) {
        emit("update:openCreateModal", newValue);
    }
})

const category = ref<any>(null);
const vicinity = ref<any>(null);
const origin = ref<any>(null);
const destination = ref<any>(null);

const mode = computed(() => {
    return props.incident ? 'update' : 'create'
})

const defaultFormValues: Incident = {
    category: '',
    nature: 'conduction',
    origin: null,
    destination: null,
    patientFirstName: '',
    patientLastName: '',
    patientMiddleName: '',
    patientBirthdate: '',
    patientAddress: '',
    chiefComplaint: '',
    vicinity: ''
};

const incidentForm = ref<Incident>(defaultFormValues);

const transportFromOption = ref('');
const transportToOption = ref('');

const emptyError = {
    category: '',
    vicinity: '',
    origin: '',
    destination: '',
};

const errors = ref(emptyError);

const searchEhrPatient = () => {
    incidentStore.fetchEhr(incidentForm.value.patientEhrId);
}

const resetEhrPatient = () => {
    ehrPatient.value = null;
    incidentForm.value.patientAddress = '';
    incidentForm.value.patientEhrId = '';
    incidentForm.value.patientFirstName = '';
    incidentForm.value.patientLastName = '';
    incidentForm.value.patientMiddleName = '';
    incidentForm.value.patientBirthdate = '';
    incidentForm.value.patientBirthdateIsFortuitous = false;
    incidentForm.value.chiefComplaint = '';
    incidentForm.value.remarks = '';
}

watch(incidentForm, (newVal) => {
    if (!newVal.patientEhrId && patientRecordSourceOption.value === 'ehr') {
        resetEhrPatient();
    }
}, {deep: true});

watch(ehrPatient, (newVal) => {
    if (newVal) {
        incidentForm.value.patientAddress = ehrPatient.value.patientAddress;
        incidentForm.value.patientFirstName = ehrPatient.value.patientFirstName;
        incidentForm.value.patientLastName = ehrPatient.value.patientLastName;
        incidentForm.value.patientMiddleName = ehrPatient.value.patientMiddleName;
        incidentForm.value.patientBirthdate = ehrPatient.value.patientBirthdate;
        incidentForm.value.patientBirthdateIsFortuitous = ehrPatient.value.patientBirthdateIsFortuitous;
        incidentForm.value.chiefComplaint = ehrPatient.value.chiefComplaint;
        incidentForm.value.remarks = ehrPatient.value.remarks;
    }
}, {deep: true});

watch(patientRecordSourceOption, (newVal) => {
    if (isUpdateInitialLoad.value) {
        isUpdateInitialLoad.value = false;
    } else {
        resetEhrPatient();
    }
}, {deep: true})

const validateForm = () => {
    
    // ---- Validate Transfer Category and Vicinity ---- //
    if (!incidentForm.value.category) {
        errors.value.category = 'Please select a category';
        if(category.value) category.value.focus();
        return false;
    }

    if (!incidentForm.value.vicinity) {
        errors.value.vicinity = 'Please select a vicinity';
        if(vicinity.value) vicinity.value.focus();
        return false;
    }
    // ---- End of Category Validation ---- //
    
    // ---- Validate Origin nd Destination ---- //
    if (!transportFromOption.value) {
        errors.value.origin = 'Please indicate the site origin';
        if(origin.value) origin.value.focus();
        return false;
    }

    if (!transportToOption.value) {
        errors.value.destination = 'Please indicate the site destination';
        if(destination.value) destination.value.focus();
        return false;
    }
    // ---- End of Origin and Destination Validation ---- //
    
    return true;
}

const handleSubmit = async () => {
    
    if(!validateForm()) return;

    let finalForm: Incident = {
        healthFacilityId: incidentForm.value.healthFacilityId,
        category: incidentForm.value.category,
        nature: incidentForm.value.nature,
        origin: null,
        destination: null,
        patientEhrId: incidentForm.value.patientEhrId,
        patientFirstName: incidentForm.value.patientFirstName,
        patientLastName: incidentForm.value.patientLastName,
        patientMiddleName: incidentForm.value.patientMiddleName,
        patientBirthdate: incidentForm.value.patientBirthdate,
        patientBirthdateIsFortuitous: incidentForm.value.patientBirthdateIsFortuitous,
        patientAddress: incidentForm.value.patientAddress,
        chiefComplaint: incidentForm.value.chiefComplaint,
        vicinity: incidentForm.value.vicinity,
        remarks: incidentForm.value.remarks
    };

    if (transportFromOption.value === 'current') {
        finalForm.fromHealthFacilityId = incidentForm.value.healthFacilityId;
    } else if (transportFromOption.value === 'facility') {
        finalForm.fromHealthFacilityId = incidentForm.value.fromHealthFacilityId;
    } else {
        finalForm.origin = incidentForm.value.origin;
    }

    if (transportToOption.value === 'current') {
        finalForm.toHealthFacilityId = incidentForm.value.healthFacilityId;
    } else if (transportToOption.value === 'facility') {
        finalForm.toHealthFacilityId = incidentForm.value.toHealthFacilityId;
    } else {
        finalForm.destination = incidentForm.value.destination;
    }

    try {
        if( mode.value === 'create') 
            await incidentStore.createIncident(finalForm, successCallback, errorCallback)
        else {
            let toUpdate: any = {};
            for (let i in finalForm) {
                if (props.incident[i] !== finalForm[i]) {
                    if (i === 'origin' && transportFromOption.value !== 'custom') continue;
                    if (i === 'destination' && transportToOption.value !== 'custom') continue;
                    toUpdate[i] = finalForm[i];
                }
            }
            
            if (Object.keys(toUpdate).length > 0) {
                toUpdate.id = props.incident.id;
                await incidentStore.updateIncident(toUpdate, successCallback, errorCallback);
            } else {
                notify({
                    type: 'success',
                    text: 'Nothing to update.'
                });
                closeAndResetModal();
            }
        }
    } catch (error) {
        console.error(error);
        notify({
            type: "error",
            title: "Error",
            text: "An error has occurred. Plese try again."
        })
    }

}

const closeAndResetModal = () => {
    incidentForm.value = defaultFormValues;
    openModal.value = false;
}

const successCallback = (message) => {
    notify({
        type: 'success',
        title: 'Success',
        text: message
    })
    closeAndResetModal();
}

const errorCallback = (error) => {
    console.error(error);
}

const resetErrors = () => {
    errors.value = {
        category: '',
        vicinity: '',
        origin: '',
        destination: ''
    };
}

const confirmDelete = async (id) => {
    const { isCanceled } = await confirm.reveal()

    if(isCanceled) return;
    
    deleteIncident(id);
}

const deleteIncident = async (id) => {
    await incidentStore.deleteIncident(id, successCallback, errorCallback);
}

watch(incidentForm, (newVal) => {
    resetErrors();
}, {deep: true});

watch(transportFromOption, (newVal) => {
    resetErrors();
}, {deep: true});

watch(transportToOption, (newVal) => {
    resetErrors();
}, {deep: true});

watch(openModal, (newVal) => {
    if (newVal) {
        isUpdateInitialLoad.value = false;
        if(mode.value === 'update') { 
            // check the origin option
            if (props.incident.healthFacilityId === props.incident.fromHealthFacilityId) {
                transportFromOption.value = 'current';
            } else if(props.incident.fromHealthFacilityId) {
                transportFromOption.value = 'facility';
            } else {
                transportFromOption.value = 'custom';
            }
            // check the destination option
            if (props.incident.healthFacilityId === props.incident.toHealthFacilityId) {
                transportToOption.value = 'current';
            } else if(props.incident.toHealthFacilityId) {
                transportToOption.value = 'facility';
            } else {
                transportToOption.value = 'custom';
            }
            // check the patient record source
            if (props.incident.patientEhrId) {
                patientRecordSourceOption.value = 'ehr';
            } else {
                patientRecordSourceOption.value = 'manual';
            }
            isUpdateInitialLoad.value = true;
            incidentForm.value = {
                healthFacilityId: props.incident.healthFacilityId,
                category: props.incident.category,
                nature: props.incident.nature,
                vicinity: props.incident.vicinity,
                fromHealthFacilityId: props.incident.fromHealthFacilityId,
                toHealthFacilityId: props.incident.toHealthFacilityId,
                origin: props.incident.origin,
                destination: props.incident.destination,
                patientEhrId: props.incident.patientEhrId,
                patientFirstName: props.incident.patientFirstName,
                patientLastName: props.incident.patientLastName,
                patientMiddleName: props.incident.patientMiddleName,
                patientBirthdate: props.incident.patientBirthdate,
                patientBirthdateIsFortuitous: props.incident.patientBirthdateIsFortuitous ? true : false,
                patientAddress: props.incident.patientAddress,
                chiefComplaint: props.incident.chiefComplaint,
                remarks: props.incident.remarks
            };
        } else {
            incidentForm.value = defaultFormValues;
            if (['hfadmin', 'opcen'].includes(user?.value?.role || '')) {
                incidentForm.value.healthFacilityId = user?.value?.healthFacilityId;
            }
        }    
    }
}, {deep: true});

onMounted(() => {
    facilityStore.getFacilityOptions();
});
</script>
