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
                Role
              </th>
              <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
                Name
              </th>
              <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
                Contact Info
              </th>
              <th scope="col" class="columns-2 border border-gray-500 text-md font-medium text-gray-900 p-2 text-left">
                Status
              </th>
              <th scope="col" class="columns-1 border border-gray-500 w-10 text-md font-medium text-gray-900 p-2 text-left">
                Action
              </th>
            </tr>
          </thead>
          <tbody class="">
            <template v-if="loadingUserList">
              <tr class="border-b border-gray-100" >
                <td colspan="7" class="p-2 border border-gray-100">
                  <Loading />
                </td>
                </tr>
            </template>
            <template v-else>
              <tr class="border-b border-gray-100" v-for="user in userList" >
                <td class="p-2 border border-gray-100">{{ user.id }}</td>
                <td class="p-2 border border-gray-100">
                  {{ user.healthFacilityName }}
                </td>
                <td class="p-2 border border-gray-100">
                  {{ user.roleLabel }}
                </td>
                <td class="p-2 border border-gray-100">
                    {{ user.lastName + ', ' + user.firstName + (user.middleName ? ' ' + user.middleName.charAt(0) + '.' : '') }}
                </td>
                <td class="p-2 border border-gray-100">
                  {{ user.email }}
                  <br>
                  {{ user.contactNoFull }}
                </td>
                <td class="p-2 border border-gray-100">
                  {{ user.statusLabel }}
                </td>
                <td class="p-1 border border-gray-100">
                    <div class=" flex justify-center items center space-x-3">
                        <button class="cursor-pointer" @click="updateUser(user)">
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
import { useUserStore } from '@/stores/user/userStore';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faEdit } from '@fortawesome/free-solid-svg-icons';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';
import { User } from '@/models/UserModel';
library.add(faEdit);

const {userList, loadingUserList} = storeToRefs(useUserStore());

const emit = defineEmits(['open-update-modal']);

const updateUser = (user: User) => {
    emit('open-update-modal', user);
}
</script>

<style scoped>

</style>