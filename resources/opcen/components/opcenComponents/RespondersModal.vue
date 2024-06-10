<template>
    <modal v-if="openModal" @close-modal="openModal = false;">
        <template #modalTitle>
            <span v-if="mode === 'create'">Create New Dispatch Request</span>
            <span v-else>Update Dispatch Request</span>
        </template>
        <template #modalBody>
            <template v-if="processingDispatch">
                <div class="w-full h-20">
                    <Loading />
                </div>
            </template>
            <template v-else>
                <form class="mx-auto" @submit.prevent="handleSubmit()">
                    <div class="px-4 mb-4 space-y-4">
                        <div class="text-gray-400 text-sm">
                            Ambulance
                        </div>
                        <label
                            for="ambulance-select"
                            class="sr-only"
                        >
                            Ambulance
                        </label>
                        <template v-if="loadingAmbulanceOptions">
                            <Loading />
                        </template>
                        <template v-else>
                            <select
                                v-model="dispatchForm.ambulanceId"
                                id="ambulance-select"
                                class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                                required
                            >
                                <option
                                    value=""
                                    disabled
                                    selected
                                >
                                    Select Ambulance
                                </option>
                                <option
                                    v-for="option in ambulanceOptions"
                                    :value="option.id">
                                        {{ option.plateNo }}
                                    </option>
                            </select>
                        </template>
                    </div>
                    <div class="px-4 mb-4 space-y-4">
                        <div class="text-gray-400 text-sm">
                            Driver
                        </div>
                        <label
                            for="driver-select"
                            class="sr-only"
                        >
                            Driver
                        </label>
                        <template v-if="loadingDriverOptions">
                            <Loading />
                        </template>
                        <template v-else>
                            <select
                                v-model="dispatchForm.driverId"
                                id="driver-select"
                                class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                                required
                            >
                                <option
                                    value=""
                                    disabled
                                    selected
                                >
                                    Select Driver
                                </option>
                                <option
                                    v-for="driver in driverOptions"
                                    :value="driver.id"
                                >
                                    {{ driver.lastName + ', ' + driver.firstName + (driver.middleName ? ' ' + driver.middleName.charAt(0) + '.' : '') }}
                                </option>
                            </select>
                        </template>
                    </div>
                    <div class="px-4 mb-4 space-y-4">
                        <div class="text-gray-400 text-sm">
                            Response Squad
                        </div>
                        <label
                            for="squad-select"
                            class="sr-only"
                        >
                            Response Squad
                        </label>
                        <template v-if="loadingSquadOptions">
                            <Loading />
                        </template>
                        <template v-else>
                            <select
                                v-model="dispatchForm.squadId"
                                id="squad-select"
                                class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                                required
                            >
                                <option
                                    value=""
                                    disabled
                                    selected
                                >
                                    Select Squad
                                </option>
                                <option
                                    v-for="squad in squadOptions"
                                    :value="squad.id">
                                        {{ squad.squadName }}
                                    </option>
                            </select>
                        </template>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex w-full justify-between">
                        <div class="self-start">
                            <delete-button
                                v-if="mode === 'update'"
                                @button-action="confirmDelete(dispatch.id)"
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
import { PropType, computed, onMounted, ref, watch } from 'vue';
import Modal from '../Modal.vue';
import ActionButton from '../form/ActionButton.vue';
import CancelButton from '../form/CancelButton.vue';
import DeleteButton from '../form/DeleteButton.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBan, faSave, faTrash } from '@fortawesome/free-solid-svg-icons';
import { notify } from '@kyvg/vue3-notification';
import { createConfirmDialog } from 'vuejs-confirm-dialog'
import ConfirmDialogue from '@/components/ConfirmDialogue.vue';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';
import { useDispatchStore } from '@/stores/dispatch/dispatchStore';
import { Dispatch } from '@/models/DispatchModel';
import { useDriverStore } from '@/stores/driver/driverStore';
import { useAmbulanceStore } from '@/stores/ambulance/ambulanceStore';
import { useSquadStore } from '@/stores/squad/squadStore';

library.add(faSave, faBan, faTrash);

const props = defineProps({
    openCreateModal: {
        type: Boolean,
        default: false
    },
    dispatch: {
        type: Object as PropType<any>,
        default: null
    },
    incident: {
        type: Object as PropType<any>,
        default: null
    }
})

const ambulanceStore = useAmbulanceStore();
const { ambulanceOptions, loadingAmbulanceOptions } = storeToRefs(ambulanceStore);

const driverStore = useDriverStore();
const { driverOptions, loadingDriverOptions } = storeToRefs(driverStore);

const squadStore = useSquadStore();
const { squadOptions, loadingSquadOptions } = storeToRefs(squadStore);

const dispatchStore = useDispatchStore();
const { processingDispatch } = storeToRefs(dispatchStore);

const confirm = createConfirmDialog(ConfirmDialogue as any);

const mode = computed(() => {
    return props.dispatch ? 'update' : 'create'
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

const password = ref<any>(null);

const defaultFormValues = {
    incidentId: 0,
    ambulanceId: 0,
    driverId: 0,
    squadId: 0,
    remarks: '',
};

const dispatchForm = ref(defaultFormValues);

const handleSubmit = async () => {
    try {
        if (mode.value === 'create') {
            await dispatchStore.createDispatch(dispatchForm.value, successCallback, errorCallback)
        } else {
            let toUpdate: any = {};
            let form = dispatchForm.value;
            for (let i in form) {
                if (props.dispatch[i] !== form[i]) {
                    toUpdate[i] = form[i];
                }
            }
            if (Object.keys(toUpdate).length > 0) {
                toUpdate.id = props.dispatch.id;
                await dispatchStore.updateDispatch(toUpdate, successCallback, errorCallback);
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
            text: "An error has occurred. Please try again."
        })
    }
}

const closeAndResetModal = () => {
    dispatchForm.value = {
        incidentId: 0,
        ambulanceId: 0,
        driverId: 0,
        squadId: 0,
        remarks: '',
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
    
    deleteDispatch(id);
}

const deleteDispatch = async (id) => {
    await dispatchStore.deleteDispatch(id, successCallback, errorCallback);
}

watch(openModal, (newVal) => {
    if (newVal) {
        loadOptions();
        if(mode.value === 'update') {
            dispatchForm.value = {
                incidentId: props.dispatch.incidentId,
                ambulanceId: props.dispatch.ambulanceId,
                driverId: props.dispatch.driverId,
                squadId: props.dispatch.squadId,
                remarks: '',
            };
        } else {
            dispatchForm.value = {
                incidentId: props.incident.id,
                ambulanceId: 0,
                driverId: 0,
                squadId: 0,
                remarks: '',
            };
        }
    }
    
}, {deep: true});

const loadOptions = () => {
    ambulanceStore.getAmbulanceOptions(props.incident.healthFacilityId);
    driverStore.getDriverOptions(props.incident.healthFacilityId);
    squadStore.getSquadOptions(props.incident.healthFacilityId);
};

onMounted(() => {
})

</script>
