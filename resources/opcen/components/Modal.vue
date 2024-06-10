<template>
<!-- Main modal -->
<div
    tabindex="-1"
    aria-hidden="true"
    class="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    :class="[
        zIndex
    ]"
>
    <div class="bg-white fixed w-full h-screen opacity-50 z-0 overflow-hidden top-0 left-0"></div>
    <div
        class="relative p-4 w-full max-h-full"
        :class="{
            'max-w-2xl': size === 'default',
            'max-w-md': size === 'small'
        }"
    >
        <!-- Modal content -->
        <div class="relative rounded-lg shadow bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                <h3 class="text-xl font-semibold text-white">
                    <slot name="modalTitle" />
                </h3>
                <button 
                    type="button"
                    class="bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                    @click="emit('closeModal')"
                >
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">                
                <slot name="modalBody" />
            </div>
            <!-- Modal footer -->
            <!-- <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="opcen-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create</button>
                <button data-modal-hide="opcen-modal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
            </div> -->
        </div>
    </div>
</div>

</template>

<script setup lang="ts">
import { PropType } from 'vue';


const props = defineProps({
    zIndex: {
        type: String,
        default: 'z-50'
    },
    size: {
        type: String as PropType<Sizes>,
        default: 'default'
    }
})

type Sizes = 'small' | 'large' | 'default';

const emit = defineEmits(['closeModal']);

</script>

<style scoped>

</style>