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
              Squad Name
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
          <template v-if="loadingSquads">
            <tr class="border-b border-gray-100" >
              <td colspan="6" class="p-2 border border-gray-100">
                <Loading />
              </td>
              </tr>
          </template>
          <template v-else>
            <tr class="border-b border-gray-100" v-for="squad in squads" >
              <td class="p-2 border border-gray-100">{{ squad.id }}</td>
              <td class="p-2 border border-gray-100">
                  {{ squad.healthFacilityName }}
              </td>
              <td class="p-2 border border-gray-100">
                  {{ squad.squadName }}
              </td>
              <td class="p-2 border border-gray-100">
                {{ squad.statusLabel }}
              </td>
              <td class="p-1 border border-gray-100">
                  <div class=" flex justify-center items center space-x-3">
                      <button class="cursor-pointer" @click="updateSquad(squad)">
                        <font-awesome-icon :icon="['fas', 'edit']" />
                      </button>
                      <button class="cursor-pointer" @click="viewCrew(squad)">
                        <font-awesome-icon :icon="['fas', 'people-group']" />
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
import { useSquadStore } from '@/stores/squad/squadStore';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faEdit, faPeopleGroup } from '@fortawesome/free-solid-svg-icons';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';
import { Squad } from '@/models/SquadModel';
library.add(faEdit, faPeopleGroup);

const {squads, loadingSquads} = storeToRefs(useSquadStore());

const emit = defineEmits(['open-update-modal', 'open-crew-modal']);

const updateSquad = (squad: Squad) => {
    emit('open-update-modal', squad);
}

const viewCrew = (squad: Squad) => {
    emit('open-crew-modal', squad);
}
</script>

<style scoped>

</style>