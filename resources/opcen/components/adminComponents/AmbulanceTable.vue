<template>
    <div class="overflow-y-auto block max-h-[67svh]">
        <table class="w-full border-separate">
        <thead class="bg-white sticky top-0">
          <tr>
            <th scope="col" class="columns-1 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              ID
            </th>
            <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Health Facility
            </th>
            <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Plate No.
            </th>
            <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Ambulance Type
            </th>
            <th scope="col" class="columns-1 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Status
            </th>
            <th scope="col" class="columns-2 border border-gray-500 w-10 text-md font-medium text-gray-900 p-2 text-left">
              Action
            </th>
          </tr>
        </thead>
        <tbody class="overflow-y-auto">
          <template v-if="loadingAmbulances">
            <tr class="border-b border-gray-100" >
              <td colspan="6" class="p-2 border border-gray-100">
                <Loading />
              </td>
              </tr>
          </template>
          <template v-else>
            <tr class="border-b border-gray-100" v-for="ambulance in ambulances" >
              <td class="p-2 border border-gray-100">{{ ambulance.id }}</td>
              <td class="p-2 border border-gray-100">
                  {{ ambulance.healthFacilityName }}
              </td>
              <td class="p-2 border border-gray-100">
                {{ ambulance.plateNo }}
              </td>
              <td class="p-2 border border-gray-100">
                {{ ambulance.type }}
              </td>
              <td class="p-2 border border-gray-100">
                {{ ambulance.statusLabel }}
              </td>
              <td class="p-1 border border-gray-100">
                  <div class=" flex justify-center items center space-x-3">
                      <button class="cursor-pointer" @click="updateAmbulance(ambulance)">
                        <font-awesome-icon :icon="['fas', 'edit']" />
                      </button>
                  </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
</template>

<script setup lang="ts">
import { useAmbulanceStore } from '@/stores/ambulance/ambulanceStore';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faEdit } from '@fortawesome/free-solid-svg-icons';
import { storeToRefs } from 'pinia';
import {Ambulance} from '@/models/AmbulanceModel'
import Loading from '../Loading.vue';
library.add(faEdit);

const {ambulances, loadingAmbulances} = storeToRefs(useAmbulanceStore());

const emit = defineEmits(['open-update-modal']);

const updateAmbulance = (ambulance: Ambulance) => {
    emit('open-update-modal', ambulance);
}
</script>

<style scoped>

</style>