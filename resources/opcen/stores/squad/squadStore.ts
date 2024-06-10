import { defineStore } from 'pinia'
import { state } from './state'
import { getters } from './getters'
import api from '@/api';
import { notify } from '@kyvg/vue3-notification';

export const useSquadStore = defineStore('squadStore', {
    state: state,
    getters: getters,
    actions: {
        async loadSquads() {
            this.loadingSquads = true;
            await api.getSquads().then(response => {
                this.squads = response.data.data;
                return this.squads;
            }).finally(() => {
                this.loadingSquads = false;
            });
        },
        async createSquad(form, successCallback, errorCallback) {
            this.processingSquad = true;
            await api.createSquad(form).then(response => {
                this.loadSquads();
                successCallback('New driver record created!');
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
                this.processingSquad = false;
            });
        },
        async updateSquad(form, successCallback, errorCallback) {
            this.processingSquad = true;
            await api.updateSquad(form).then(response => {
                this.loadSquads();
                successCallback('Squad record updated!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingSquad = false;
            });
        },
        async deleteSquad(id, successCallback, errorCallback) {
            this.processingSquad = true;
            await api.deleteSquad(id).then(response => {
                this.loadSquads();
                successCallback('Squad record deleted!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingSquad = false;
            });
        },

        // squad crew members actions
        async loadSquadMembers($squadId) {
            this.loadingSquadMembers = true;
            await api.getCrewMembers($squadId).then(response => {
                this.squadCrewMembers = response.data.data.crewMembers;
                this.unassignedEmts = response.data.data.unassignedEmts;
                return this.squadCrewMembers;
            }).finally(() => {
                this.loadingSquadMembers = false;
            });
        },
        async addSquadMember(form, successCallback, errorCallback) {
            this.processingSquadMember = true;
            await api.addCrewMember(form).then(response => {
                this.loadSquadMembers(form.squadId);
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
                this.processingSquadMember = false;
            });
        },
        async removeSquadMember(data, successCallback, errorCallback) {
            this.processingSquadMember = true;
            await api.removeCrewMember(data.emtId).then(response => {
                this.loadSquadMembers(data.squadId);
                successCallback('Squad member removed!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingSquadMember = false;
            });
        },
        async getSquadOptions(facilityId) {
            this.loadingSquadOptions = true;
            await api.getSquadOptions(facilityId).then(response => {
                this.squadOptions = response.data.data;
                return this.squadOptions;
            }).finally(() => {
                this.loadingSquadOptions = false;
            });
        },
    }
});