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
                <UserTable @open-update-modal="openUpdateModal"></UserTable>
            </div>
        </div>
        <UserFormModal v-model:open-create-modal="openCreateModal" :user="userData" />
    </div>
</template>

<script setup lang="ts">
import ActionButton from '@/components/form/ActionButton.vue';
import { onMounted, ref } from 'vue';
import { Facility } from '@/models/FacilityModel';
import UserTable from '@/components/adminComponents/UserTable.vue';
import UserFormModal from '@/components/adminComponents/UserFormModal.vue';
import { useUserStore } from '@/stores/user/userStore';

const openCreateModal = ref(false);

const triggerCreateModal = () => {
    openCreateModal.value = true;
    userData.value = null;
}

const userStore = useUserStore();

const userData = ref<any>(null);

const openUpdateModal = (facility: Facility) => {
    openCreateModal.value = true;
    userData.value = facility;
}

onMounted(() => {
    userStore.loadUserList();
})
</script>

<style scoped>

</style>