import { NotificationState } from "@/models/NotificationModel";

export const state = () :NotificationState => {
    return {
        notifications: null,
        loadingNotifications: false
    }
}