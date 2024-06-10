<template>
    <modal v-if="openModal" @close-modal="openModal = false;">
        <template #modalTitle>
            Create New Transport Request
        </template>
        <template #modalBody>
            <form class="mx-auto" @submit.prevent="handleSubmit()">
                <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                    Nature of Operation
                </div>
                <div class="px-4 mb-4">
                    <fieldset>
                        <legend class="sr-only">Nature of Operation</legend>
                        <div class="flex items-center mb-4">
                            <input
                                id="type-option-1"
                                type="radio"
                                name="natureOfOperation"
                                value="conduction"
                                class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                checked>
                            <label for="type-option-1" class="block ms-2 text-sm font-medium text-gray-300">
                                Conduction
                            </label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input
                                id="type-option-2"
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
                    <select
                        id="transfer-category-select"
                        class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white ring-blue-500 focus:border-blue-500"
                        placeholder="Select a category"    
                    >
                        <option selected>Select a Category</option>
                        <option value="step-up">Step up</option>
                        <option value="step-down">Step down</option>
                    </select>
                    <select
                        id="transfer-area-coverage-select"
                        class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white ring-blue-500 focus:border-blue-500"
                        placeholder="Select area coverage"    
                    >
                        <option selected>Select Area Coverage</option>
                        <option value="within-la-union">Within La Union</option>
                        <option value="outside-la-union">Outside La Union</option>
                    </select>
                </div>
                <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                    Journey
                </div>
                <div class="px-4">
                    <map-selection></map-selection>
                </div>
                <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                    Patient Information
                </div>
                <div class="px-4">
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text" 
                            name="floating-patient-id" 
                            id="floating-patient-id" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                        <label
                            for="floating-patient-id"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Patient ID</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text" 
                            name="floating-patient-name" 
                            id="floating-patient-name" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                        <label
                            for="floating-patient-name"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Patient/Requester Name</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">                        
                        <label
                            for="chief-complaints"
                            class="block mb-2 text-sm font-medium text-white"
                            >Chief Complaints</label>
                        <textarea
                            id="chief-complaints"
                            rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Patient's chief complaint..."
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
                            rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Remarks..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-4">
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
                
            </form>
        </template>
    </modal>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import Modal from '../Modal.vue';
import MapSelection from '../map/MapSelection.vue';
import ActionButton from '../form/ActionButton.vue';

const props = defineProps({
    openCreateModal: {
        type: Boolean,
        default: false
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

const transportForm = ref({
    natureOfOperation: null,
    categoryOfTransfer: null
})

const handleSubmit = () => {
    console.log('Click submit');
}
</script>
