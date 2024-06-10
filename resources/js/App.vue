<template>
  <div class="p-0 mx-auto">
    <template v-if="isLoggedIn">
      <div class="w-full">
        <top-navigation />
      </div>
    </template>
    <RouterView class="view main-content w-full"></RouterView>
    <DialogsWrapper />
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/user/userStore';
import { defineAsyncComponent, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const TopNavigation = defineAsyncComponent(() => import('@/components/layout/TopNavigation.vue'));

  const isLoggedIn = ref(false);
  const router = useRouter();
  const userStore = useUserStore();

  onMounted(() => {
    if (window.Laravel.isLoggedin) {
      isLoggedIn.value = true;
      let user = window.Laravel.user;
      userStore.user = {
        id: user.id,
        healthFacilityId: user.health_facility_id,
        firstName: user.first_name,
        lastName: user.last_name,
        middleName: user.middle_name,
        username: user.username,
        email: user.email,
        contactNo: user.contact_no,
        role: user.role
      }
    } else {
      router.push({name: "Login"});
    }
  });
</script>

<style scoped></style>