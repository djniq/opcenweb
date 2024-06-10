<template>
    <modal v-if="openModal" @close-modal="openModal = false;">
        <template #modalTitle>
            <span v-if="mode === 'create'">Create New Ambulance</span>
            <span v-else>Update Ambulance</span>
        </template>
        <template #modalBody>
            <template v-if="processingAmbulance">
                <div class="w-full h-20">
                    <Loading />
                </div>
            </template>
            <template v-else>
                <form class="mx-auto" @submit.prevent="handleSubmit()">
                    <div v-if="user.role === 'superadmin'" class="px-4 mb-8 space-y-4">
                        <div class="text-gray-400 text-sm">
                            Health Facility
                        </div>
                        <label
                            for="level-select"
                            class="sr-only"
                        >
                            Health Facility
                        </label>
                        <select
                            v-model="ambulanceForm.healthFacilityId"
                            id="health-facility-select"
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
                    </div>
                    <div class="px-4">
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="text" 
                                name="floating-ambulance-plate-no"
                                id="floating-ambulance-plate-no"
                                v-model="ambulanceForm.plateNo"
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                            <label
                                for="floating-ambulance-plate-no"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >Plate No.</label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="text" 
                                v-model="ambulanceForm.type"
                                name="floating-ambulance-type" 
                                id="floating-ambulance-type" 
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                            <label
                                for="floating-ambulance-type"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >Ambulance Type</label>
                        </div>
                    </div>
                    <div class="px-4 mb-4">
                        <fieldset>
                            <legend class="sr-only">Status</legend>
                            <div class="flex items-center mb-4">
                                <input
                                    id="ambulance-status-1"
                                    v-model="ambulanceForm.status"
                                    type="radio"
                                    name="ambulanceStatus"
                                    value="1"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                    checked>
                                <label for="ambulance-status-1" class="block ms-2 text-sm font-medium text-gray-300">
                                    Active
                                </label>
                            </div>

                            <div class="flex items-center mb-4">
                                <input
                                    id="ambulance-status-2"
                                    v-model="ambulanceForm.status"
                                    type="radio"
                                    name="ambulanceStatus"
                                    value="2"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                >
                                <label for="ambulance-status-2" class="block ms-2 text-sm font-medium text-gray-300">
                                    Inactive
                                </label>
                            </div>
                        </fieldset>
                    </div>
                    <!-- Buttons -->
                    <div class="flex w-full justify-between">
                        <div class="self-start">
                            <delete-button
                                v-if="mode === 'update'"
                                @button-action="confirmDelete(ambulanceForm.id)"
                            >
                                <font-awesome-icon :icon="['fa', 'trash']" /> Delete
                            </delete-button>
                        </div>
                        <div class="self-end">
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
import { PropType, computed, onMounted, ref, watch } from 'vue';
import Modal from '../Modal.vue';
import ActionButton from '../form/ActionButton.vue';
import CancelButton from '../form/CancelButton.vue';
import DeleteButton from '../form/DeleteButton.vue';
import { useHealthFacilityStore } from '@/stores/healthFacility/healthFacilityStore';
import { Facility } from '@/models/FacilityModel';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBan, faSave, faTrash } from '@fortawesome/free-solid-svg-icons';
import { notify } from '@kyvg/vue3-notification';
import { createConfirmDialog } from 'vuejs-confirm-dialog'
import ConfirmDialogue from '@/components/ConfirmDialogue.vue';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';
import { Ambulance } from '@/models/AmbulanceModel';
import { useAmbulanceStore } from '@/stores/ambulance/ambulanceStore';
import { useUserStore } from '@/stores/user/userStore';

library.add(faSave, faBan, faTrash);

const props = defineProps({
    openCreateModal: {
        type: Boolean,
        default: false
    },
    ambulance: {
        type: Object as PropType<Ambulance>,
        default: null
    }
})

const facilityStore = useHealthFacilityStore();
const { facilityOptions, loadingOptions } = storeToRefs(facilityStore);

const ambulanceStore = useAmbulanceStore();
const { processingAmbulance } = storeToRefs(ambulanceStore);

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const confirm = createConfirmDialog(ConfirmDialogue as any);

const mode = computed(() => {
    return props.ambulance ? 'update' : 'create'
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

const defaultFormValues: Ambulance = {
    healthFacilityId: 0,
    plateNo: '',
    type: '',
    status: 1
};

const ambulanceForm = ref(defaultFormValues);

const handleSubmit = async () => {
    try {
        let save = mode.value === 'create' 
            ? await ambulanceStore.createAmbulance(ambulanceForm.value, successCallback, errorCallback)
            : await ambulanceStore.updateAmbulance(ambulanceForm.value, successCallback, errorCallback);
    } catch (error) {
        console.error(error);
        notify({
            type: "error",
            title: "Error",
            text: error?.response.data.message
        })
    }
}

const closeAndResetModal = () => {
    ambulanceForm.value = {
        healthFacilityId: 0,
        plateNo: '',
        type: '',
        status: 1
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
    
    deleteAmbulance(id);
}

const deleteAmbulance = async (id) => {
    await ambulanceStore.deleteAmbulance(id, successCallback, errorCallback);
}

watch(openModal, (newVal) => {
    if (newVal) {
        if(mode.value === 'update') {
            ambulanceForm.value = props.ambulance;
        } else {
            ambulanceForm.value = {
                healthFacilityId: 0,
                plateNo: '',
                type: '',
                status: 1
            };
            if (userStore.user?.role !== 'superadmin') {
                ambulanceForm.value.healthFacilityId = userStore.user?.healthFacilityId;
            }
        }
    }
    
}, {deep: true});

onMounted(() => {
    if (user?.value?.role === 'superadmin') {
        facilityStore.getFacilityOptions();
    }
    
})

</script>
