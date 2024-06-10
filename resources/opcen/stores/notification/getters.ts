import { NotificationState } from '@/models/NotificationModel';

export const getters = {
    getNotifications(state: NotificationState) {
        return state.notifications;
    }
}