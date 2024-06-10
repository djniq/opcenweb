<template>
    <div class="w-full px-2 md:px-5">
    <TabMenu v-if="tabItems" :tab-items="tabItems" />
    <RouterView />
    <notifications position="bottom center" />
  </div>
</template>

<script setup lang="ts">
import TabMenu from '@/components/layout/TabMenu.vue';
import { SubMenuItem } from '@/models/Tab';
import { useUserStore } from '@/stores/user/userStore';
import { computed, ref } from 'vue';

const userStore = useUserStore();

const tabItems = computed(() => userStore.user?.role === 'superadmin' ? superAdminTabItems : adminTabItems);
const superAdminTabItems: SubMenuItem[] = [
  {
    path: '/admin',
    name: 'AdminOverview',
    label: 'Overview'
  },
  {
    path: '/admin/health-facilities',
    name: 'HealthFacilities',
    label: 'Health Facilities'
  },
  {
    path: '/admin/ambulances',
    name: 'Ambulances',
    label: 'Ambulances'
  },
  {
    path: '/admin/drivers',
    name: 'Drivers',
    label: 'Drivers'
  },
  {
    path: '/admin/responders',
    name: 'Responders',
    label: 'Responders'
  },
  {
    path: '/admin/users',
    name: 'Users',
    label: 'Users'
  },
];

const adminTabItems: SubMenuItem[] = [
  {
    path: '/admin',
    name: 'AdminOverview',
    label: 'Overview'
  },
  {
    path: '/admin/ambulances',
    name: 'Ambulances',
    label: 'Ambulances'
  },
  {
    path: '/admin/drivers',
    name: 'Drivers',
    label: 'Drivers'
  },
  {
    path: '/admin/responders',
    name: 'Responders',
    label: 'Responders'
  },
  {
    path: '/admin/users',
    name: 'Users',
    label: 'Users'
  },
];
</script>

<style scoped>

</style>