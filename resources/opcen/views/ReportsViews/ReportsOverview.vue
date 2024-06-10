<template>
    <div class="flex flex-col justify-center w-full space-y-24">
        <div class="flex w-full space-x-10 justify-center">
            <div class="w-full md:w-1/4 flex flex-col justify-center items-center">
                <div class="text-[5rem] leading-normal font-bold">
                    <template v-if="loadingReportsCounters">
                        <Loading></Loading>
                    </template>
                    <template v-else-if="dispatchReportsCounters">
                        {{ dispatchReportsCounters.conductions }}
                    </template>
                </div>
                <div class="text-lg">Total No. of Conductions</div>
                <div class="h-20 w-20"><img src="@/assets/img/map-route.svg"  class="w-full h-full mt-2"/></div>
            </div>
            <div class="w-full md:w-1/4 flex flex-col justify-center items-center">
                <div class="text-[5rem] leading-normal font-bold">
                    <template v-if="loadingReportsCounters">
                        <Loading></Loading>
                    </template>
                    <template v-else-if="dispatchReportsCounters">
                        {{ dispatchReportsCounters.availableAmbulances }}/<span class="text-[3rem]">{{ dispatchReportsCounters.totalAmbulances }}</span>
                    </template>
                </div>
                <div class="text-lg">Available Ambulances</div>
                <div class="h-20 w-20"><img src="@/assets/img/ambulance-icon.svg"  class="w-full h-full mt-2"/></div>
            </div>
            <div class="w-full md:w-1/4 flex flex-col justify-center items-center">
                <div class="text-[5rem] leading-normal font-bold">
                    <template v-if="loadingReportsCounters">
                        <Loading></Loading>
                    </template>
                    <template v-else-if="dispatchReportsCounters">
                        {{ dispatchReportsCounters.averageConductionMinute }}<span class="text-[3rem]">min</span>
                    </template>
                </div>
                <div class="text-lg">Average Duration of Conduction</div>
                <div class="h-20 w-20"><img src="@/assets/img/stopwatch.svg"  class="w-full h-full mt-2"/></div>
            </div>
            <div class="w-full md:w-1/4 flex flex-col justify-center items-center">
                <div class="text-[5rem] leading-normal font-bold">
                    <template v-if="loadingReportsCounters">
                        <Loading></Loading>
                    </template>
                    <template v-else-if="dispatchReportsCounters">
                        {{ dispatchReportsCounters.availableSquads }}/<span class="text-[3rem]">{{ dispatchReportsCounters.totalSquads }}</span>
                    </template>
                </div>
                <div class="text-lg">Squads</div>
                <div class="h-20 w-20"><img src="@/assets/img/group-icon.svg"  class="w-full h-full mt-2"/></div>
            </div>
        </div>
        <div class="flex w-full space-x-10 justify-center">
            <div class="w-full md:w-1/4 flex flex-col justify-center items-center">
                <div class="text-[5rem] leading-normal font-bold">
                    <template v-if="loadingReportsCounters">
                        <Loading></Loading>
                    </template>
                    <template v-else-if="dispatchReportsCounters">
                        {{ dispatchReportsCounters.emergencyTraumaCount }}
                    </template>
                </div>
                <div class="text-lg">Total No. of ED(Trauma)</div>
            </div>
            <div class="w-full md:w-1/4 flex flex-col justify-center items-center">
                <div class="text-[5rem] leading-normal font-bold">
                    <template v-if="loadingReportsCounters">
                        <Loading></Loading>
                    </template>
                    <template v-else-if="dispatchReportsCounters">
                        {{ dispatchReportsCounters.averageMovingOutMinute }}<span class="text-[3rem]">min</span>
                    </template>
                </div>
                <div class="text-lg">Ave. Time of Arrival to Moveout</div>
            </div>
            <div class="w-full md:w-1/4 flex flex-col justify-center items-center">
                <div class="text-[5rem] leading-normal font-bold">
                    <template v-if="loadingReportsCounters">
                        <Loading></Loading>
                    </template>
                    <template v-else-if="dispatchReportsCounters">
                        {{ dispatchReportsCounters.emergencyMedicalCount }}
                    </template>
                </div>
                <div class="text-lg">Total No. of ED(Medical)</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Loading from '@/components/Loading.vue';
import { useDispatchStore } from '@/stores/dispatch/dispatchStore';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';

const dispatchStore = useDispatchStore();
const { dispatchReportsCounters, loadingReportsCounters } = storeToRefs(dispatchStore);

onMounted(() => {
    dispatchStore.loadReportsCounters();
})
</script>

<style scoped>

</style>