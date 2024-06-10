<template>
  <nav class="bg-gradient-to-r from-white to-yellow-500 border-b-1 border-b-gray-500 dark:bg-gray-900 mb-4">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <NotificationBadge v-can:is-emt class="mr-4"/>
        <button type="button"
          class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
          id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
          data-dropdown-placement="bottom">
          <span class="sr-only">Open user menu</span>
          <img class="w-8 h-8 rounded-full" src="@/assets/img/default-avatar.png" alt="user avatar">
        </button>
        <!-- Dropdown menu -->
        <div
          class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
          id="user-dropdown">
          <div class="px-4 py-3">
            <span v-if="user" class="block text-sm text-gray-900 dark:text-white">
              {{ user.lastName + ', ' + user.firstName + (user.middleName ? ' ' + user.middleName.charAt(0) + '.' : '') }}
            </span>
          </div>
          <ul class="py-2" aria-labelledby="user-menu-button">
            <li>
              <a href="#"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">My Details</a>
            </li>
            <li>
              <a 
                @click.prevent="logout()"
                class="block px-4 py-2 cursor-pointer text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
                out</a>
            </li>
          </ul>
        </div>
        <button data-collapse-toggle="navbar-user" type="button"
          class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          aria-controls="navbar-user" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M1 1h15M1 7h15M1 13h15" />
          </svg>
        </button>
      </div>
      <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
        <img
          src="@/assets/img/opcen-logo.png"
          alt="OpCen"
          class="w-10 h-auto"
        >
        <ul
          class="flex flex-col font-medium p-4 md:p-0 mt-4 border-b border-b-gray-500 rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
          <li v-can:view-opcen>
            <router-link :to="{name: 'Opcen'}"
              class="block py-2 px-3"
              :class="[
                route.name === 'Opcen'
                  ? 'text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500'
                  : 'text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700'
              ]"
              aria-current="page">Opcen</router-link>
          </li>
          <li v-can:is-emt>
            <router-link :to="{name: 'Active Dispatch'}"
              class="block py-2 px-3"
              :class="[
                ['Active Dispatch', 'Dispatch History'].includes(String(route.name))
                  ? 'text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500'
                  : 'text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700'
              ]"
            >EMT</router-link>
          </li>
          <li>
            <router-link :to="{name: 'ReportsOverview'}"
              class="block py-2 px-3"
              :class="[
                route.name === 'ReportsOverview'
                  ? 'text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500'
                  : 'text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700'
              ]"
              >Reports</router-link>
          </li>
          <li v-can:view-admin>
            <router-link :to="{name: 'AdminOverview'}"
              class="block py-2 px-3"
              :class="[
                route.name === 'AdminOverview'
                  ? 'text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500'
                  : 'text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700'
              ]"
              >Administrator</router-link>
          </li>
      </ul>
    </div>
  </div>
</nav></template>

<script setup lang="ts">
import { useUserStore } from '@/stores/user/userStore';
import axios from 'axios';
import { initFlowbite } from 'flowbite';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import NotificationBadge from '@/components/NotificationBadge.vue'

const route = useRoute();

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const logout = () => {
    axios.get('/sanctum/csrf-cookie').then(response => {
      axios.post('/api/logout')
          .then(response => {
              if (response.data.success) {
                  window.location.href = "/"
              } else {
                  console.error(response)
              }
          })
          .catch(function (error) {
              console.error(error);
          });
    })
  }


onMounted(() => {
  initFlowbite();
});
</script>

<style scoped>
</style>