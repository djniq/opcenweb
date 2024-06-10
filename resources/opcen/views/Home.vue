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
        <div class="w-full flex flex-col md:flex-row h-[77svh]">
            <div class="w-full border border-gray-300  p-4">
                <IncidentTable @open-update-modal="openUpdateModal"/>
            </div>
        </div>
        <TransportRequestModal v-model:open-create-modal="openCreateModal" :incident="incidentData"/>    
    </div>
</template>

<script setup lang="ts">
import ActionButton from '@/components/form/ActionButton.vue';
import TransportRequestModal from '@/components/opcenComponents/TransportRequestModal.vue';
import IncidentTable from '@/components/opcenComponents/IncidentTable.vue';
import { ref } from 'vue';

const incidentData = ref<any>(null);

const openCreateModal = ref(false);

const triggerCreateModal = () => {
    openCreateModal.value = true;
}

const openUpdateModal = (incident) => {
    openCreateModal.value = true;
    incidentData.value = incident;
}

</script>

<style scoped></style>