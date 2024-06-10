<template>
  <div class="w-full px-2 md:px-5">
    <div v-if="loadingDispatchCounters">
      <Loading class="self-start" />
    </div>
    <div v-else class="pb-4">
      <div v-if="dispatchCounters">Total # of Response: {{ dispatchCounters.responses }}</div>
      <div v-if="dispatchCounters">Emergency Case: {{ dispatchCounters.emergency }}</div>
    </div>
    <TabMenu :tab-items="tabItems" />
    <RouterView />
  </div>
</template>

<script setup lang="ts">
import Loading from '@/components/Loading.vue';
import TabMenu from '@/components/layout/TabMenu.vue';
import { SubMenuItem } from '@/models/Tab';
import { useDispatchStore } from '@/stores/dispatch/dispatchStore';
import { storeToRefs } from 'pinia';
import { onMounted, ref } from 'vue';

const dispatchStore = useDispatchStore();
const { loadingDispatchCounters, dispatchCounters } = storeToRefs(dispatchStore);

const tabItems = ref<SubMenuItem[]>([
  {
    path: '/emt',
    name: 'Active Dispatch',
    label: 'Active'
  },
  {
    path: '/emt/dispatch-history',
    name: 'Dispatch History',
    label: 'Previous Assignments'
  },
])

onMounted(() => {
  dispatchStore.loadDispatchCounters();
});
</script>

<style scoped></style>