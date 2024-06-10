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

const userStore = useUserStore();

const tabItems: SubMenuItem[] = [
  {
    path: '/reports',
    name: 'ReportsOverview',
    label: 'Overview'
  },
  {
    path: '/reports/list',
    name: 'ReportsList',
    label: 'Create Reports'
  },
];
</script>

<style scoped>

</style>