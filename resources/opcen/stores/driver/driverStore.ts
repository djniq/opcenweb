import { defineStore } from 'pinia'
import { state } from './state'
import { getters } from './getters'
import api from '@/api';
import { notify } from '@kyvg/vue3-notification';

export const useDriverStore = defineStore('driverStore', {
    state: state,
    getters: getters,
    actions: {
        async loadDrivers() {
            this.loadingDrivers = true;
            await api.getDrivers().then(response => {
                this.drivers = response.data.data;
                return this.drivers;
            }).finally(() => {
                this.loadingDrivers = false;
            });
        },
        async createDriver(form, successCallback, errorCallback) {
            this.processingDriver = true;
            await api.createDriver(form).then(response => {
                this.loadDrivers();
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
                this.processingDriver = false;
            });
        },
        async updateDriver(form, successCallback, errorCallback) {
            this.processingDriver = true;
            await api.updateDriver(form).then(response => {
                this.loadDrivers();
                successCallback('Driver record updated!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingDriver = false;
            });
        },
        async deleteDriver(id, successCallback, errorCallback) {
            this.processingDriver = true;
            await api.deleteDriver(id).then(response => {
                this.loadDrivers();
                successCallback('Driver record deleted!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingDriver = false;
            });
        },
        async getDriverOptions(facilityId) {
            this.loadingDriverOptions = true;
            await api.getDriverOptions(facilityId).then(response => {
                this.driverOptions = response.data.data;
                return this.driverOptions;
            }).finally(() => {
                this.loadingDriverOptions = false;
            });
        },
    }
});