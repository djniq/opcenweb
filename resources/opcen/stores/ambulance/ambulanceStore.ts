import { defineStore } from 'pinia'
import { state } from './state'
import { getters } from './getters'
import api from '@/api';
import { notify } from '@kyvg/vue3-notification';

export const useAmbulanceStore = defineStore('ambulanceStore', {
    state: state,
    getters: getters,
    actions: {
        async loadAmbulances() {
            this.loadingAmbulances = true;
            await api.getAmbulances().then(response => {
                this.ambulances = response.data.data;
                return this.ambulances;
            }).finally(() => {
                this.loadingAmbulances = false;
            });
        },
        async createAmbulance(form, successCallback, errorCallback) {
            this.processingAmbulance = true;
            await api.createAmbulance(form).then(response => {
                this.loadAmbulances();
                if (response.data.success) {
                    successCallback(response.data.message);
                } else {
                    notify({
                        type: "error",
                        text: response.data.message
                    })
                }
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
                this.processingAmbulance = false;
            });
        },
        async updateAmbulance(form, successCallback, errorCallback) {
            this.processingAmbulance = true;
            await api.updateAmbulance(form).then(response => {
                this.loadAmbulances();
                successCallback('Ambulance record updated!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingAmbulance = false;
            });
        },
        async deleteAmbulance(id, successCallback, errorCallback) {
            this.processingAmbulance = true;
            await api.deleteAmbulance(id).then(response => {
                this.loadAmbulances();
                successCallback('Ambulance record deleted!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingAmbulance = false;
            });
        },
        async getAmbulanceOptions(healthFacilityId) {
            this.loadingAmbulanceOptions = true;
            await api.getAmbulanceOptions(healthFacilityId).then(response => {
                this.ambulanceOptions = response.data.data;
                return this.ambulanceOptions;
            }).finally(() => {
                this.loadingAmbulanceOptions = false;
            });
        },
    }
});