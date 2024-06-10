import { defineStore } from 'pinia'
import { state } from './state'
import { getters } from './getters'
import api from '@/api';
import { notify } from '@kyvg/vue3-notification';

export const useHealthFacilityStore = defineStore('healthFacilityStore', {
    state: state,
    getters: getters,
    actions: {
        async loadFacilities() {
            this.loadingFacilities = true;
            await api.getFacilities().then(response => {
                this.facilities = response.data.data;
                return this.facilities;
            }).finally(() => {
                this.loadingFacilities = false;
            });
        },
        async getAssetCounters() {
            this.loadingCounters = true;
            await api.getFacilityAssetCounters().then(response => {
                this.assetCounters = response.data;
                return this.assetCounters;
            }).finally(() => {
                this.loadingCounters = false;
            });
        },
        async getFacilityOptions() {
            this.loadingOptions = true;
            await api.getFacilityOptions().then(response => {
                this.facilityOptions = response.data.data;
                return this.facilityOptions;
            }).finally(() => {
                this.loadingOptions = false;
            });
        },
        async createFacility(form, successCallback, errorCallback) {
            this.processingFacility = true;
            await api.createFacility(form).then(response => {
                this.loadFacilities();
                successCallback('New health facility created!');
                return response.data;
            }).catch((e) => {
                console.error(e.response.data);
                notify({
                    type: "error",
                    text: e.response.data.message
                  })
                  errorCallback(e);
                  return null;
            }).finally(() => {
                this.processingFacility = false;
            });
        },
        async updateFacility(form, successCallback, errorCallback) {
            this.processingFacility = true;
            await api.updateFacility(form).then(response => {
                this.loadFacilities();
                successCallback('Health facility updated!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingFacility = false;
            });
        },
        async deleteFacility(id, successCallback, errorCallback) {
            this.processingFacility = true;
            await api.deleteFacility(id).then(response => {
                this.loadFacilities();
                successCallback('Health facility deleted!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingFacility = false;
            });
        }
    }
});