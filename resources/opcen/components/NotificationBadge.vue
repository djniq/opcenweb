<template>
    <button v-if="notifications && notifications.unreadCount" @click="triggerCreateModal()" type="button" class="relative mr-4 inline-flex items-center p-2 text-sm font-medium text-center text-white bg-gray-400 rounded-full hover:bg-red-800 focus:ring-4 focus:outline-none">
        <font-awesome-icon class="h-5" :icon="['fas', 'bell']" />
        <span class="sr-only">Notifications</span>
        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -end-2 dark:border-gray-900">
            {{ notifications ? notifications.unreadCount : 0 }}
        </div>
    </button>
    <NotificationModal  v-if="notifications && notifications.unreadCount" v-model:open-notification-modal="openNotificationModal" />
</template>

<script setup lang="ts">
import { useNotificationStore } from '@/stores/notification/notificationStore';
import { storeToRefs } from 'pinia';
import {onMounted, ref, inject, watch} from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBell } from '@fortawesome/free-solid-svg-icons';
import NotificationModal from '@/components/emtComponents/NotificationModal.vue'
import Echo from 'laravel-echo';

library.add(faBell);

const notifStore = useNotificationStore();
const { notifications, loadingNotifications } = storeToRefs(notifStore);

const openNotificationModal = ref(false);

const triggerCreateModal = () => {
    openNotificationModal.value = true;
}

watch(notifications, (val) => {
    if (val && val.unreadCount === 0) {
        openNotificationModal.value = false;
    } else if (val && val.unreadCount) {
        openNotificationModal.value = true;
    }
}, {deep: true});

var reloadInterval:any = null;

onMounted(() => {
    notifStore.loadNotifications();
    openNotificationModal.value = true;
    // const echo = inject('echo');
    // echo.private('dispatch').listen('DispatchCreated', e => {
    //     console.log(e);
    // })
    // Alternative notification loading
    if (reloadInterval) {
        clearInterval(reloadInterval);
    }
    reloadInterval = setInterval(() => {
        notifStore.loadNotifications();
    }, 7000)
});
</script>

<style scoped>

</style>