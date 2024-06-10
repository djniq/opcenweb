<template>
    <modal v-if="openModal" @close-modal="openModal = false">
        <template #modalTitle>
            Proceed?
        </template>
        <template #modalBody>
            <div class="flex w-full flex-end">
                <action-button @button-action="emit('confirm')">Confirm</action-button>
                <cancel-button @click="closeModal()">Cancel</cancel-button>
            </div>
        </template>
    </modal>
</template>

<script lang="ts" setup>
    import Modal from '@/components/Modal.vue';
    import ActionButton from '@/components/form/ActionButton.vue';
    import CancelButton from '@/components/form/CancelButton.vue';
    import { onMounted, ref } from 'vue';
    const emit = defineEmits(['confirm', 'cancel']);

    const openModal = ref(true);

    const closeModal = () => {
        emit('cancel');
        openModal.value = false;
    };

    onMounted(() => {
        openModal.value = true;
    });
</script>