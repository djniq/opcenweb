<template>
    <div class="overflow-y-auto block max-h-[67svh]">
        <table class="w-full border-separate">
        <thead class="bg-white sticky top-0">
          <tr>
            <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              ID
            </th>
            <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Health Facility
            </th>
            <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Driver's Name
            </th>
            <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
              Status
            </th>
            <th scope="col" class="columns-2 border border-gray-500 w-10 text-md font-medium text-gray-900 p-2 text-left">
              Action
            </th>
          </tr>
        </thead>
        <tbody class="overflow-y-auto">
          <template v-if="loadingDrivers">
            <tr class="border-b border-gray-100" >
              <td colspan="6" class="p-2 border border-gray-100">
                <Loading />
              </td>
              </tr>
          </template>
          <template v-else>
            <tr class="border-b border-gray-100" v-for="driver in drivers" >
              <td class="p-2 border border-gray-100">{{ driver.id }}</td>
              <td class="p-2 border border-gray-100">
                  {{ driver.lastName + ', ' + driver.firstName + (driver.middleName ? ' ' + driver.middleName.charAt(0) + '.' : '') }}
              </td>
              <td class="p-2 border border-gray-100">
                  {{ driver.healthFacilityName }}
              </td>
              <td class="p-2 border border-gray-100">
                {{ driver.statusLabel }}
              </td>
              <td class="p-1 border border-gray-100">
                  <div class=" flex justify-center items center space-x-3">
                      <button class="cursor-pointer" @click="updateDriver(driver)">
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
import { useDriverStore } from '@/stores/driver/driverStore';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faEdit } from '@fortawesome/free-solid-svg-icons';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';
import { Driver } from '@/models/DriverModel';
library.add(faEdit);

const {drivers, loadingDrivers} = storeToRefs(useDriverStore());

const emit = defineEmits(['open-update-modal']);

const updateDriver = (driver: Driver) => {
    emit('open-update-modal', driver);
}
</script>

<style scoped>

</style>