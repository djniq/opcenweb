<template>
  <section class="bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <div class="w-full sm:max-w-md flex items-center justify-center py-5">
        <img
          src="@/assets/img/itrmc-logo.png"
          alt="ITRMC"
          class="w-40 h-auto"
        >
        <img
          src="@/assets/img/opcen-logo.png"
          alt="OpCen"
          class="w-40 h-auto"
        >
      </div>
      <div
        class="w-full rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 bg-gray-800 border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight md:text-2xl text-white">
            Sign in to your account
          </h1>
          
          <form
            class="space-y-4 md:space-y-6"
            @submit.prevent="handleSubmit">
            <div>
              <label
                for="username"
                class="block mb-2 text-sm font-medium text-white">Username</label>
              <input
                type="text"
                name="username"
                autocomplete="username"
                v-model="username"
                id="username"
                class="border sm:text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-yellow-200 focus:border-yellow-200"
                placeholder="Your Username"
                required>
            </div>
            <div>
              <label
                for="password"
                class="block mb-2 text-sm font-medium text-white"
              >Password</label>
              <input
                type="password"
                name="password"
                v-model="password"
                autocomplete="current-password"
                id="password"
                placeholder="••••••••"
                class="border sm:text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 dark:text-white focus:ring-yellow-200 focus:border-yellow-200"
                required>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-start">
                <!-- <div class="flex items-center h-5">
                  <input id="remember" aria-describedby="remember" type="checkbox"
                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800"
                    required="">
                </div> -->
                <!-- <div class="ml-3 text-sm">
                  <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                </div> -->
              </div>
              <!-- <a href="#" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot
                password?</a> -->
            </div>
            <button
              type="submit"
              class="w-full font-medium rounded-lg text-sm text-white px-5 py-2.5 border-2 border-gray-600 shadow
                text-center bg-gradient-to-r from-red-900 to-orange-500 hover:bg-primary-700 focus:ring-primary-800
                hover:border-yellow-200 hover:bg-gradient-to-l"
                >Sign in</button>
            <!-- <p class="text-sm font-light text-gray-500 dark:text-gray-400">
              Don’t have an account yet? <a href="#"
                class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign up</a>
            </p> -->
          </form>
        </div>
      </div>
    </div>
    <notifications position="top center" />
  </section>
</template>

<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { useNotification } from "@kyvg/vue3-notification";

const { notify } = useNotification();

const username = ref('');
const password = ref('');
const error = ref('');

const handleSubmit = () => {
  if (password.value.length > 0) {
      axios.get('/sanctum/csrf-cookie').then(response => {
          axios.post('api/login', {
              username: username.value,
              password: password.value
          })
              .then(response => {
                  if (response.data.success) {
                    window.location.href = "/";
                  } else {
                      error.value = response.data.message;
                      notify({
                        type: "error",
                        text: error.value
                      })
                  }
              })
              .catch(function (error) {
                  console.error(error);
              });
      })
  }
}
</script>

<style scoped></style>