<template>
    <div class="w-full px-2 md:px-5">
        <div class="w-full p-2 rounded-t-md bg-gradient-to-r from-gray-600 to-orange-500">
            <action-button
                class=""
                @button-action="triggerCreateModal()"
            >
                Create New Squads
            </action-button>
        </div>
        <div class="w-full flex flex-col md:flex-row h-[70svh]">
            <div class="w-full border border-gray-300  p-4">
                <SquadTable @open-update-modal="openUpdateModal" @open-crew-modal="viewCrewMembers"></SquadTable>
            </div>
        </div>
        <SquadFormModal v-model:open-create-modal="openCreateModal" :squad="squadData" />
        <CrewMembersModal v-model:open-crew-modal="openCrewModal" :squad="squadData" />
    </div>
</template>

<script setup lang="ts">
import ActionButton from '@/components/form/ActionButton.vue';
import { onMounted, ref } from 'vue';
import { useSquadStore } from '@/stores/squad/squadStore';
import SquadTable from '@/components/adminComponents/SquadTable.vue';
import SquadFormModal from '@/components/adminComponents/SquadFormModal.vue';
import { Squad } from '@/models/SquadModel';
import CrewMembersModal from '@/components/adminComponents/CrewMembersModal.vue';

const openCreateModal = ref(false);
const openCrewModal = ref(false);

const triggerCreateModal = () => {
    openCreateModal.value = true;
    squadData.value = null;
}

const squadStore = useSquadStore();

const squadData = ref<any>(null);

const openUpdateModal = (squad: Squad) => {
    openCreateModal.value = true;
    squadData.value = squad;
}

const viewCrewMembers = (squad: Squad) => {
    openCrewModal.value = true;
    squadData.value = squad;
}

onMounted(() => {
    squadStore.loadSquads();
})
</script>

<style scoped>

</style>