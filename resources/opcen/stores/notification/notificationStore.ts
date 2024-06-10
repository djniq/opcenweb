import { defineStore } from 'pinia'
import { state } from './state'
import { getters } from './getters'
import api from '@/api';
import { notify } from '@kyvg/vue3-notification';

export const useNotificationStore = defineStore('notificationStore', {
    state: state,
    getters: getters,
    actions: {
        async loadNotifications() {
            this.loadingNotifications = true;
            await api.getNotifications().then(response => {
                this.notifications = response.data;
                return this.notifications;
            }).finally(() => {
                this.loadingNotifications = false;
            });
        },
        async markAsRead() {
            await api.markAsRead().then(response => {
                this.notifications = response.data;
                return this.notifications;
            }).catch((e) => {
                console.error(e);
                return null;
            });
        },
    }
});