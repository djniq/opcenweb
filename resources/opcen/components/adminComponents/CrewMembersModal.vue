<template>
    <modal v-if="openModal" @close-modal="openModal = false;">
        <template #modalTitle>
            Crew Members
        </template>
        <template #modalBody>
            <template v-if="loadingSquadMembers">
                <div class="w-full h-20">
                    <Loading />
                </div>
            </template>
            <template v-else>
                <!-- Crew Members -->
                <div class="border border-gray-400 p-4 w-full mb-4">
                    <div class="text-center font-bold text-lg">Crew Members</div>
                    <div class="flex flex-col space-y-4">
                        <div v-for="(member, index) in squadCrewMembers" class="rounded-lg bg-gray-400 shadow-lg text-sm flex flex-start w-fit p-2">
                            {{ member.user.last_name + ', ' + member.user.first_name + (member.user.middle_name ? ' ' + member.user.middle_name.charAt(0) + '.' : '') }}
                            <button 
                                class="inline flex items-center justify-center ml-2 border p-2 rounded-full border-gray-700 hover:border-red-400 text-gray-700 hover:text-red-400 bg-gray-300 h-3 w-3"
                                @click="removeMember(member.user.id, index)"
                            >
                                <font-awesome-icon class="h-2 w-2" :icon="['fas', 'x']" />
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Unassigned EMT staff -->
                <div class="border border-gray-400 p-4 w-full mb-4">
                    <div class="text-center font-bold text-lg">Unassigned EMT</div>
                    <div class="flex flex-col space-y-4">
                        <div
                            v-for="(emt, index) in unassignedEmts"
                            class="rounded-lg bg-gray-400 shadow-lg text-sm flex flex-start w-fit p-2 transition duration-500 ease-in-out"
                        >
                            {{ emt.last_name + ', ' + emt.first_name + (emt.middle_name ? ' ' + emt.middle_name.charAt(0) + '.' : '') }}
                            <button
                                class="inline flex items-center justify-center ml-2 border p-2 rounded-full border-gray-700 hover:border-green-400 text-gray-700 hover:text-green-400 bg-gray-300 h-3 w-3"
                                @click="addMember(emt.id, index)"
                            >
                                <font-awesome-icon class="h-2 w-2" :icon="['fas', 'plus']" />
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </template>   
    </modal>
</template>

<script setup lang="ts">
import { PropType, computed, onMounted, ref, watch } from 'vue';
import Modal from '../Modal.vue';
import { useHealthFacilityStore } from '@/stores/healthFacility/healthFacilityStore';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBan, faPlus, faSave, faTrash, faX } from '@fortawesome/free-solid-svg-icons';
import { notify } from '@kyvg/vue3-notification';
import { createConfirmDialog } from 'vuejs-confirm-dialog'
import ConfirmDialogue from '@/components/ConfirmDialogue.vue';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';
import { useSquadStore } from '@/stores/squad/squadStore';
import { Squad } from '@/models/SquadModel';


library.add(faSave, faBan, faTrash, faX, faPlus);

const props = defineProps({
    openCrewModal: {
        type: Boolean,
        default: false
    },
    squad: {
        type: Object as PropType<Squad>,
        default: null
    }
})

const facilityStore = useHealthFacilityStore();
const { facilityOptions, loadingOptions } = storeToRefs(facilityStore);

const squadStore = useSquadStore();
const { squadCrewMembers, loadingSquadMembers, unassignedEmts } = storeToRefs(squadStore);

const confirm = createConfirmDialog(ConfirmDialogue);

const emit = defineEmits<{
  (e: "update:openCrewModal", value: Boolean): void;
}>();

const openModal = computed<boolean>({
    get() {
        return props.openCrewModal;
    },
    set(newValue) {
        emit("update:openCrewModal", newValue);
    }
})

const defaultFormValues = {
    healthFacilityId: 0,
    status: 1
};

const emtForm = ref(defaultFormValues);

const addMember = async (emtId, index) => {
   await squadStore.addSquadMember({emtId: emtId, squadId: props.squad.id}, successCallback, errorCallback);
}

const removeMember = async (emtId, index) => {
   await squadStore.removeSquadMember({emtId: emtId, squadId: props.squad.id}, successCallback, errorCallback);
}

const successCallback = (message) => {
    notify({
        type: 'success',
        title: 'Success',
        text: message
    })
}

const errorCallback = (error) => {
    console.error(error);
}

const confirmDelete = async (id) => {
    const { isCanceled } = await confirm.reveal()

    if(isCanceled) return;
    
    deleteCrew(id);
}

const deleteCrew = async (emtId) => {
    await squadStore.removeSquadMember(emtId, successCallback, errorCallback);
}

watch(openModal, (newVal) => {
    if (newVal) {
        squadStore.loadSquadMembers(props.squad.id);
    }
    
}, {deep: true});

onMounted(() => {
})

</script>
