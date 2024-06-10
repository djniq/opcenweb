<template>
    <div class="w-full px-2 md:px-5">
        <div class="w-full p-2 rounded-t-md bg-gradient-to-r from-gray-600 to-orange-500">
            <action-button
                class=""
                @button-action="triggerCreateModal()"
            >
                Create New
            </action-button>
        </div>
        <div class="w-full flex flex-col md:flex-row h-[70svh]">
            <div class="w-full border border-gray-300  p-4">
                <AmbulanceTable @open-update-modal="openUpdateModal"></AmbulanceTable>
            </div>
        </div>
        <AmbulanceFormModal v-model:open-create-modal="openCreateModal" :ambulance="ambulanceData" />
    </div>
</template>

<script setup lang="ts">
import ActionButton from '@/components/form/ActionButton.vue';
import { onMounted, ref } from 'vue';
import AmbulanceTable from '@/components/adminComponents/AmbulanceTable.vue';
import AmbulanceFormModal from '@/components/adminComponents/AmbulanceFormModal.vue';
import { useAmbulanceStore } from '@/stores/ambulance/ambulanceStore';
import { Ambulance } from '@/models/AmbulanceModel';

const openCreateModal = ref(false);

const triggerCreateModal = () => {
    openCreateModal.value = true;
    ambulanceData.value = null;
}

const ambulanceStore = useAmbulanceStore();

const ambulanceData = ref<any>(null);

const openUpdateModal = (ambulance: Ambulance) => {
    openCreateModal.value = true;
    ambulanceData.value = ambulance;
}

onMounted(() => {
    ambulanceStore.loadAmbulances();
})
</script>

<style scoped>

</style>