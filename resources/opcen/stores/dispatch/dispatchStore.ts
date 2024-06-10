import { defineStore } from 'pinia'
import { state } from './state'
import { getters } from './getters'
import api from '@/api';
import { notify } from '@kyvg/vue3-notification';

import { useIncidentStore as incidentStore } from '../incident/incidentStore';

export const useDispatchStore = defineStore('dispatchStore', {
    state: state,
    getters: getters,
    actions: {
        async loadDispatches() {
            this.loadingDispatches = true;
            await api.getDispatches().then(response => {
                this.dispatches = response.data.data;
                return this.dispatches;
            }).finally(() => {
                this.loadingDispatches = false;
            });
        },
        async createDispatch(form, successCallback, errorCallback) {
            this.processingDispatch = true;
            await api.createDispatch(form).then(response => {
                this.loadDispatches();
                incidentStore().loadIncidents();
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
                this.processingDispatch = false;
            });
        },
        async updateDispatch(form, successCallback, errorCallback) {
            this.processingDispatch = true;
            await api.updateDispatch(form).then(response => {
                this.loadDispatches();
                incidentStore().loadIncidents();
                successCallback('Dispatch record updated!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingDispatch = false;
            });
        },
        async updateDispatchStatus(data, successCallback, errorCallback) {
            this.processingDispatch = true;
            await api.updateDispatch(data).then(response => {
                this.loadActiveDispatch();
                successCallback('Dispatch status updated!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingDispatch = false;
            });
        },
        async updateDispatchLocation(data) {
            await api.updateDispatch(data).then(response => {
                this.activeDispatch.dispatch.last_recorded_location = data.last_recorded_location;
                return response;
            }).catch((e) => {
                console.error(e);
                return null;
            }).finally(() => {
                
            });
        },
        async deleteDispatch(id, successCallback, errorCallback) {
            this.processingDispatch = true;
            await api.deleteDispatch(id).then(response => {
                this.loadDispatches();
                successCallback('Dispatch record deleted!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingDispatch = false;
            });
        },
        async loadActiveDispatch() {
            this.loadingActiveDispatch = true;
            await api.loadActiveDispatch().then(response => {
                this.activeDispatch = response.data.data ? response.data.data[0] : null;
                return response.data;
            }).catch((e) => {
                return null;
            }).finally(() => {
                this.loadingActiveDispatch = false;
            });
        },
        async loadDispatchCounters() {
            this.loadingDispatchCounters = true;
            await api.loadDispatchCounters().then(response => {
                this.dispatchCounters = response.data;
                return response.data;
            }).catch((e) => {
                return null;
            }).finally(() => {
                this.loadingDispatchCounters = false;
            });
        },
        async loadReports(dateRange = null) {
            this.loadingReports = true;
            await api.loadDispatchReports(dateRange).then(response => {
                this.dispatchReports = response.data.data;
                return response.data;
            }).catch((e) => {
                return null;
            }).finally(() => {
                this.loadingReports = false;
            });
        },
        async loadReportsCounters() {
            this.loadingReportsCounters = true;
            await api.loadDispatchReportsCounters().then(response => {
                this.dispatchReportsCounters = response.data;
                return response.data;
            }).catch((e) => {
                return null;
            }).finally(() => {
                this.loadingReportsCounters = false;
            });
        },
        async loadEmtDispatches() {
            this.loadingReports = true;
            await api.loadEmtDispatches().then(response => {
                this.emtDispatches = response.data.data;
                return response.data;
            }).catch((e) => {
                return null;
            }).finally(() => {
                this.loadingReports = false;
            });
        },
        async exportDispatchReport(dateRange = null) {
            await api.exportDispatchReport(dateRange).then(response => {
                const blob = new Blob([response.data], { type: 'text/csv' })
                const link = document.createElement('a')
                link.href = URL.createObjectURL(blob)
                link.download = 'download'
                link.click()
                URL.revokeObjectURL(link.href)
                return response;
            }).catch((e) => {
                console.error(e);
                return null;
            }).finally(() => {
                
            });
        },
    }
});