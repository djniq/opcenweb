<template>
    <button
        :type="buttonType"
        class="font-medium rounded-lg text-sm text-white px-5 py-2.5 border-2 border-gray-600 shadow text-center bg-gradient-to-r from-red-700 to-red-500 hover:bg-primary-700 focus:ring-primary-800 hover:border-yellow-200 hover:bg-gradient-to-l"
        @click="emit('buttonAction')"
    >
        <slot></slot>
    </button>
</template>

<script setup lang="ts">
import { ButtonHTMLAttributes, PropType } from 'vue';


const props = defineProps({
    buttonType: {
        type: String as PropType<ButtonHTMLAttributes["type"]>,
        default: "button"
    },
})

const emit = defineEmits(['buttonAction']);

</script>

<style scoped>

</style>