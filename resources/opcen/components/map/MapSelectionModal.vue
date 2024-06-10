<template>
    <modal v-model:show-modal="openModal" z-index="z-100">
        <template #modalBody>
            <MapSelection></MapSelection>
        </template>
    </modal>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import Modal from '../Modal.vue';
import MapSelection from './MapSelection.vue';

const props = defineProps({
    openLocationModal: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits<{
  (e: "update:openLocationModal", value: Boolean): void;
}>();

const openModal = computed<boolean>({
    get() {
        return props.openLocationModal;
    },
    set(newValue) {
        emit("update:openLocationModal", newValue);
    }
})
</script>

<style scoped>

</style>