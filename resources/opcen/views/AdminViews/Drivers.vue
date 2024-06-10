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
                <DriverTable @open-update-modal="openUpdateModal"></DriverTable>
            </div>
        </div>
        <DriverFormModal v-model:open-create-modal="openCreateModal" :driver="driverData" />
    </div>
</template>

<script setup lang="ts">
import ActionButton from '@/components/form/ActionButton.vue';
import { onMounted, ref } from 'vue';
import { useDriverStore } from '@/stores/driver/driverStore';
import DriverTable from '@/components/adminComponents/DriverTable.vue';
import DriverFormModal from '@/components/adminComponents/DriverFormModal.vue';
import { Driver } from '@/models/DriverModel';

const openCreateModal = ref(false);

const triggerCreateModal = () => {
    openCreateModal.value = true;
    driverData.value = null;
}

const driverStore = useDriverStore();

const driverData = ref<any>(null);

const openUpdateModal = (driver: Driver) => {
    openCreateModal.value = true;
    driverData.value = driver;
}

onMounted(() => {
    driverStore.loadDrivers();
})
</script>

<style scoped>

</style>