import { defineStore } from 'pinia';
import { state } from './state';
import { getters } from './getters';
import api from '@/api';
import { notify } from '@kyvg/vue3-notification';

const useUser = defineStore('userStore', {
    state: state,
    getters: getters,
    actions: {
        async loadUserList() {
            this.loadingUserList = true;
            await api.getUsers().then(response => {
                this.userList = response.data.data;
                return this.userList;
            }).finally(() => {
                this.loadingUserList = false;
            });
        },
        async createUser(form, successCallback, errorCallback) {
            this.processingUser = true;
            await api.createUser(form).then(response => {
                this.loadUserList();
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
                this.processingUser = false;
            });
        },
        async updateUser(form, successCallback, errorCallback) {
            this.processingUser = true;
            await api.updateUser(form).then(response => {
                this.loadUserList();
                successCallback('User record updated!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingUser = false;
            });
        },
        async deleteUser(id, successCallback, errorCallback) {
            this.processingUser = true;
            await api.deleteUser(id).then(response => {
                this.loadUserList();
                successCallback('User record deleted!');
                return response;
            }).catch((e) => {
                errorCallback(e);
                return null;
            }).finally(() => {
                this.processingUser = false;
            });
        }
    }
});

export const useUserStore = useUser;