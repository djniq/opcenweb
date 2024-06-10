<template>
  <div class="w-full px-2 md:px-5">
    <div v-if="loadingReports" class="w-full flex justify-center items-center">
      <Loading />
    </div>
    <div v-else class="overflow-y-auto block max-h-[67svh]">
      <div v-if="!emtDispatches" class="w-full flex justify-center items-center h-[67svh] font-bold font-italic">
        No Reports Found
      </div>
      <table v-else class="w-full border-separate">
        <thead v-if="!loadingReports" class="bg-white sticky top-0">
          <tr>
            <th
              v-for="header in emtDispatches.tableHeaders"
              class="border border-gray-500 text-md font-medium text-gray-900 p-2 text-left w-[50%]"
            >
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody class="overflow-y-auto">
          <tr class="border-b border-gray-100" v-for="content in emtDispatches.content" >
            <td v-for="item in content" class="p-2 border border-gray-100">{{ item }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';
import { useDispatchStore } from '@/stores/dispatch/dispatchStore';
import ActionButton from '@/components/form/ActionButton.vue';
import { onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import Loading from '@/components/Loading.vue';
import { faDownload } from '@fortawesome/free-solid-svg-icons';

library.add(faDownload);

const dispatchStore = useDispatchStore();
const { loadingReports, emtDispatches } = storeToRefs(dispatchStore);

const downloadReport = () => {
  dispatchStore.exportDispatchReport();
}

onMounted(()=> {
  dispatchStore.loadEmtDispatches();
});
</script>

<style scoped></style>