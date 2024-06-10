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
                <HealthFacilityTable @open-update-modal="openUpdateModal"></HealthFacilityTable>
            </div>
        </div>
        <HealthFacilityFormModal v-model:open-create-modal="openCreateModal" :facility="facilityData" />
    </div>
</template>

<script setup lang="ts">
import ActionButton from '@/components/form/ActionButton.vue';
import HealthFacilityTable from '@/components/adminComponents/HealthFacilityTable.vue';
import HealthFacilityFormModal from '@/components/adminComponents/HealthFacilityFormModal.vue';
import { onMounted, ref } from 'vue';
import { useHealthFacilityStore } from '@/stores/healthFacility/healthFacilityStore';
import { storeToRefs } from 'pinia';
import { Facility } from '@/models/FacilityModel';

const openCreateModal = ref(false);

const triggerCreateModal = () => {
    openCreateModal.value = true;
    facilityData.value = null;
}

const facilityStore = useHealthFacilityStore();
const { facilities, loadingFacilities } = storeToRefs(facilityStore);

const facilityData = ref<any>(null);

const openUpdateModal = (facility: Facility) => {
    openCreateModal.value = true;
    facilityData.value = facility;
}

onMounted(() => {
    facilityStore.loadFacilities();
})
</script>

<style scoped>

</style>