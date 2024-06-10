<template>
    <modal v-if="openModal" @close-modal="openModal = false;">
        <template #modalTitle>
            New Dispatch Request
        </template>
        <template #modalBody>
            <div class="relative p-4">
                New Dispatch ID: {{ notifications.notifications[0].data.dispatch.id }}
                <br>
                Go to the Active Dispatch page now
            </div>
            <action-button @click="confirmNotification()">
            Confirm and Go
            </action-button>
                            
        </template>
        
    </modal>
</template>

<script setup lang="ts">
import { PropType, computed, onMounted, ref, watch } from 'vue';
import Modal from '@/components/Modal.vue';
import ActionButton from '@/components/form/ActionButton.vue';
import { useNotificationStore } from '@/stores/notification/notificationStore';
import { storeToRefs } from 'pinia';

const props = defineProps({
    openNotificationModal: {
        type: Boolean,
        default: false
    }
})

const notifStore = useNotificationStore();
const { notifications } = storeToRefs(notifStore);

const emit = defineEmits<{
  (e: "update:openNotificationModal", value: Boolean): void;
}>();

const openModal = computed<boolean>({
    get() {
        return props.openNotificationModal;
    },
    set(newValue) {
        emit("update:openNotificationModal", newValue);
    }
})

const confirmNotification = async () => {
    try {
        await notifStore.markAsRead();
        location.href = '/'
    } catch (error) {
        console.error(error);
    }
}

onMounted(() => {
    
})

</script>
