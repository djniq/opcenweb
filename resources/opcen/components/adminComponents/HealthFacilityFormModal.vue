<template>
    <modal v-if="openModal" @close-modal="openModal = false;">
        <template #modalTitle>
            <span v-if="mode === 'create'">Create New Health Facility</span>
            <span v-else>Update Health Facility</span>
        </template>
        <template #modalBody>
            <template v-if="processingFacility">
                <div class="w-full h-20">
                    <Loading />
                </div>
            </template>
            <template v-else>
                <form class="mx-auto" @submit.prevent="handleSubmit()">
                <div class="px-4">
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text" 
                            name="floating-facility-name"
                            id="floating-facility-name"
                            v-model="healthFacilityForm.name"
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                        <label
                            for="floating-facility-name"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Health Facility Name</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="email" 
                            v-model="healthFacilityForm.email"
                            name="floating-facility-email" 
                            id="floating-facility-email" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                        <label
                            for="floating-facility-email"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Email</label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input 
                            type="text" 
                            v-model="healthFacilityForm.contactNo"
                            name="floating-contact-no" 
                            id="floating-contact-no" 
                            class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                        <label
                            for="floating-contact-no"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Contact No.</label>
                    </div>
                </div>
                <div class="px-4 mb-8 space-y-4">
                    <div class="text-gray-400 text-sm">
                        Health Facility Level
                    </div>
                    <label
                        for="level-select"
                        class="sr-only"
                    >
                        Health Facility Level
                    </label>
                    <select
                        v-model="healthFacilityForm.level"
                        id="level-select"
                        class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                        required
                    >
                        <option value="" disabled selected>Select Level</option>
                        <option value="primary">Primary</option>
                        <option value="secondary">Secondary</option>
                        <option value="tertiary">Tertiary</option>
                        <option value="specialized">Specialized</option>
                    </select>
                </div>
                <div class="px-4">
                    <AddressSelection
                        v-model:form-address="healthFacilityForm.address"
                        required
                    />
                </div>
                <div class="flex w-full justify-between">
                    <div class="self-start">
                        <delete-button
                            v-if="mode === 'update'"
                            @button-action="confirmDelete(healthFacilityForm.id)"
                        >
                            <font-awesome-icon :icon="['fa', 'trash']" /> Delete
                        </delete-button>
                    </div>
                    <div class="self-end space-x-2">
                        <action-button
                            type="submit"
                        >
                        <font-awesome-icon :icon="['fa', 'save']" /> Save
                        </action-button>
                        <cancel-button
                            @button-action="openModal = false"
                        >
                            <font-awesome-icon :icon="['fa', 'ban']" /> Cancel
                        </cancel-button>
                    </div>
                </div>
            </form>
            </template>
        </template>
        
    </modal>
</template>

<script setup lang="ts">
import { PropType, computed, ref, watch } from 'vue';
import Modal from '../Modal.vue';
import ActionButton from '../form/ActionButton.vue';
import CancelButton from '../form/CancelButton.vue';
import DeleteButton from '../form/DeleteButton.vue';
import AddressSelection from '../map/AddressSelection.vue';
import { useHealthFacilityStore } from '@/stores/healthFacility/healthFacilityStore';
import { Facility } from '@/models/FacilityModel';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBan, faSave, faTrash } from '@fortawesome/free-solid-svg-icons';
import { notify } from '@kyvg/vue3-notification';
import { createConfirmDialog } from 'vuejs-confirm-dialog'
import ConfirmDialogue from '@/components/ConfirmDialogue.vue';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';

library.add(faSave, faBan, faTrash);

const props = defineProps({
    openCreateModal: {
        type: Boolean,
        default: false
    },
    facility: {
        type: Object as PropType<Facility>,
        default: null
    }
})

const facilityStore = useHealthFacilityStore();
const { processingFacility } = storeToRefs(facilityStore);

const confirm = createConfirmDialog(ConfirmDialogue);

const mode = computed(() => {
    return props.facility ? 'update' : 'create'
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

const defaultFormValues: Facility = {
    name: '',
    level: '',
    email: '',
    contactNo: '',
    address: undefined
};

const healthFacilityForm = ref(defaultFormValues);

const handleSubmit = async () => {
    try {
        let save = mode.value === 'create' 
            ? await facilityStore.createFacility(healthFacilityForm.value, successCallback, errorCallback)
            : await facilityStore.updateFacility(healthFacilityForm.value, successCallback, errorCallback);
            
    } catch (error) {
        console.error(error);
        notify({
            type: "error",
            title: "Error",
            text: "Some error occurred. Plese try again."
        })
    }
}

const closeAndResetModal = () => {
    healthFacilityForm.value = {
        name: '',
        level: '',
        email: '',
        contactNo: '',
        address: undefined
    };
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

const confirmDelete = async (id) => {
    const { isCanceled } = await confirm.reveal()

    if(isCanceled) return;
    
    deleteFacility(id);
}

const deleteFacility = async (id) => {
    await facilityStore.deleteFacility(id, successCallback, errorCallback);
}

watch(openModal, (newVal) => {
    if (newVal) {
        if(mode.value === 'update') {
            healthFacilityForm.value = props.facility;
        } else {
            healthFacilityForm.value = {
                name: '',
                level: '',
                email: '',
                contactNo: '',
                address: undefined
            };
        }
    }
    
}, {deep: true});

</script>
