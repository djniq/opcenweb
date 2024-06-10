<template>
    <div class="flex w-full h-[60svh] space-x-20 justify-center">
        <div class="w-full md:w-1/5 flex flex-col justify-center items-center" v-can:is-superadmin>
            <div class="text-[5em] leading-normal font-bold">
                <template v-if="loadingCounters">
                    <Loading></Loading>
                </template>
                <template v-else>
                    {{ assetCounters.facilities }}
                </template>
            </div>
            <div class="text-2xl">Health Facilities</div>
            <div class="h-20 w-20"><img src="@/assets/img/hospital-clinic-icon.svg"  class="w-full h-full mt-2"/></div>
        </div>
        <div class="w-full md:w-1/5 flex flex-col justify-center items-center">
            <div class="text-[5em] leading-normal font-bold">
                <template v-if="loadingCounters">
                    <Loading></Loading>
                </template>
                <template v-else>
                    {{ assetCounters.ambulances }}
                </template>
            </div>
            <div class="text-2xl">Ambulances</div>
            <div class="h-20 w-20"><img src="@/assets/img/ambulance-icon.svg"  class="w-full h-full mt-2"/></div>
        </div>
        <div class="w-full md:w-1/5 flex flex-col justify-center items-center">
            <div class="text-[5em] leading-normal font-bold">
                <template v-if="loadingCounters">
                    <Loading></Loading>
                </template>
                <template v-else>
                    {{ assetCounters.drivers }}
                </template>
            </div>
            <div class="text-2xl">Drivers</div>
            <div class="h-20 w-20"><img src="@/assets/img/driver-icon.svg"  class="w-full h-full mt-2"/></div>
        </div>
        <div class="w-full md:w-1/5 flex flex-col justify-center items-center">
            <div class="text-[5em] leading-normal font-bold">
                <template v-if="loadingCounters">
                    <Loading></Loading>
                </template>
                <template v-else>
                    {{ assetCounters.responders }}
                </template>
            </div>
            <div class="text-2xl">Responders</div>
            <div class="h-20 w-20"><img src="@/assets/img/group-icon.svg"  class="w-full h-full mt-2"/></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Loading from '@/components/Loading.vue';
import { useHealthFacilityStore } from '@/stores/healthFacility/healthFacilityStore';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';

const facilityStore = useHealthFacilityStore();
const { assetCounters, loadingCounters } = storeToRefs(facilityStore);

onMounted(() => {
    facilityStore.getAssetCounters();
})
</script>

<style scoped>

</style>