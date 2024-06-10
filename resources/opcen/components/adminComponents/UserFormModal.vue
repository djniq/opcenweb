<template>
    <modal v-if="openModal" @close-modal="openModal = false;">
        <template #modalTitle>
            <span v-if="mode === 'create'">Create New User Record</span>
            <span v-else>Update User Record</span>
        </template>
        <template #modalBody>
            <template v-if="processingUser">
                <div class="w-full h-20">
                    <Loading />
                </div>
            </template>
            <template v-else>
                <form class="mx-auto" @submit.prevent="handleSubmit()">
                    <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                        Profile
                    </div>
                    <div class="px-4 mb-4 space-y-4">
                        <div class="text-gray-400 text-sm">
                            User Role
                        </div>
                        <label
                            for="level-select"
                            class="sr-only"
                        >
                            User Role
                        </label>
                        <select
                            v-model="userForm.role"
                            id="health-facility-select"
                            class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                            required
                        >
                            <option
                                value=""
                                disabled
                                selected
                            >
                                Select Role
                            </option>
                            <option
                                v-for="option in roleOptions"
                                :value="option.value">
                                    {{ option.label }}
                                </option>
                        </select>
                    </div>
                    <div v-can:is-superadmin v-if="userForm.role !== 'superadmin'" class="px-4 mb-4 space-y-4">
                        <div class="text-gray-400 text-sm">
                            Health Facility
                        </div>
                        <label
                            for="level-select"
                            class="sr-only"
                        >
                            Health Facility
                        </label>
                        <template v-if="loadingOptions">
                            <Loading />
                        </template>
                        <template v-else>
                            <select
                                v-model="userForm.healthFacilityId"
                                id="health-facility-select"
                                class="font-medium border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-white text-white ring-blue-500 focus:border-blue-500"   
                                required
                            >
                                <option
                                    value=""
                                    disabled
                                    selected
                                >
                                    Select Health Facility
                                </option>
                                <option
                                    v-for="option in facilityOptions"
                                    :value="option.id">
                                        {{ option.name }}
                                    </option>
                            </select>
                        </template>
                    </div>
                    <div class="px-4 mb-4">
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="text" 
                                name="floating-user-first-name"
                                id="floating-user-first-name"
                                v-model="userForm.firstName"
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                            <label
                                for="floating-user-first-name"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >First Name</label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="text" 
                                v-model="userForm.lastName"
                                name="floating-user-last-name" 
                                id="floating-user-last-name" 
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " required />
                            <label
                                for="floating-user-last-name"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >Last Name</label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="text" 
                                v-model="userForm.middleName"
                                name="floating-user-middle-name" 
                                id="floating-user-middle-name" 
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " />
                            <label
                                for="floating-user-middle-name"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >Middle Name</label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="email" 
                                v-model="userForm.email"
                                name="floating-user-email" 
                                id="floating-user-email" 
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " />
                            <label
                                for="floating-user-email"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Email</label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group flex">
                            <div class="pr-2 flex items-center text-gray-400">(+63)</div>
                            <input
                                type="number"
                                maxlength="10" 
                                v-model="userForm.contactNo"
                                name="floating-user-contact" 
                                id="floating-user-contact" 
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " />
                            <label
                                for="floating-user-contact"
                                class="peer-focus:font-medium ml-12 peer-focus:ml-0 absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Contact No.</label>
                        </div>
                    </div>
                    <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                        Login Credentials
                    </div>
                    <div class="px-4 mb-4">
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="text" 
                                v-model="userForm.username"
                                name="floating-user-username" 
                                id="floating-user-username" 
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " />
                            <label
                                for="floating-user-username"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Login Username</label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="password"
                                ref="password"
                                v-model="userForm.password"
                                name="floating-user-password" 
                                id="floating-user-password" 
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer"
                                placeholder=" " />
                            <label
                                for="floating-user-password"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Password</label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input 
                                type="password" 
                                v-model="userForm.checkPassword"
                                name="floating-user-check-password" 
                                id="floating-user-check-password" 
                                class="block py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer" placeholder=" " />
                            <label
                                for="floating-user-check-password"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >Retype Password</label>
                        </div>
                    </div>
                    <div class="text-lg font-semibold border-b border-gray mb-4 text-gray-200">
                        Status
                    </div>
                    <div class="px-4 mb-4">
                        <fieldset>
                            <legend class="sr-only">Status</legend>
                            <div class="flex items-center mb-4">
                                <input
                                    id="user-status-1"
                                    v-model="userForm.status"
                                    type="radio"
                                    name="userStatus"
                                    value="1"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                    checked>
                                <label for="user-status-1" class="block ms-2 text-sm font-medium text-gray-300">
                                    Active
                                </label>
                            </div>

                            <div class="flex items-center mb-4">
                                <input
                                    id="user-status-2"
                                    v-model="userForm.status"
                                    type="radio"
                                    name="userStatus"
                                    value="2"
                                    class="w-4 h-4 focus:ring-2 text-red-600 focus:ring-yellow-600 focus:bg-red-600 bg-gray-700 border-gray-600"
                                >
                                <label for="user-status-2" class="block ms-2 text-sm font-medium text-gray-300">
                                    Inactive
                                </label>
                            </div>
                        </fieldset>
                    </div>
                    <!-- Buttons -->
                    <div class="flex w-full justify-between">
                        <div class="self-start">
                            <delete-button
                                v-if="mode === 'update'"
                                @button-action="confirmDelete(userForm.id)"
                            >
                                <font-awesome-icon :icon="['fa', 'trash']" /> Delete
                            </delete-button>
                        </div>
                        <div class="self-end space-x-2">
                            <action-button
                                type="submit"
                            >
                            <font-awesome-icon :icon="['fa', 'save']" /> Save
                            </action-button>
                            <cancel-button
                                @button-action="openModal = false"
                            >
                                <font-awesome-icon :icon="['fa', 'ban']" /> Cancel
                            </cancel-button>
                        </div>
                    </div>
                </form>
            </template>
        </template>
        
    </modal>
</template>

<script setup lang="ts">
import { PropType, computed, onMounted, ref, watch } from 'vue';
import Modal from '../Modal.vue';
import ActionButton from '../form/ActionButton.vue';
import CancelButton from '../form/CancelButton.vue';
import DeleteButton from '../form/DeleteButton.vue';
import { useHealthFacilityStore } from '@/stores/healthFacility/healthFacilityStore';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBan, faSave, faTrash } from '@fortawesome/free-solid-svg-icons';
import { notify } from '@kyvg/vue3-notification';
import { createConfirmDialog } from 'vuejs-confirm-dialog'
import ConfirmDialogue from '@/components/ConfirmDialogue.vue';
import { storeToRefs } from 'pinia';
import Loading from '../Loading.vue';
import { useUserStore } from '@/stores/user/userStore';
import { User, UserForm } from '@/models/UserModel';

library.add(faSave, faBan, faTrash);

const props = defineProps({
    openCreateModal: {
        type: Boolean,
        default: false
    },
    user: {
        type: Object as PropType<User>,
        default: null
    }
})

const facilityStore = useHealthFacilityStore();
const { facilityOptions, loadingOptions } = storeToRefs(facilityStore);

const userStore = useUserStore();
const { processingUser, user } = storeToRefs(userStore);

const confirm = createConfirmDialog(ConfirmDialogue as any);

const mode = computed(() => {
    return props.user ? 'update' : 'create'
})

const emit = defineEmits<{
  (e: "update:openCreateModal", value: Boolean): void;
}>();

const openModal = computed<boolean>({
    get() {
        return props.openCreateModal;
    },
    set(newValue) {
        emit("update:openCreateModal", newValue);
    }
})

const roleOptions = computed(() => user?.value?.role === 'superadmin' ? superadminRoleOptions : hfAdminRoleOptions);

const superadminRoleOptions = [
    {
        value: 'superadmin',
        label: 'Super Admin'
    },
    {
        value: 'hfadmin',
        label: 'Health Facility Administrator'
    },
    {
        value: 'opcen',
        label: 'Primary OpCen User'
    },
    {
        value: 'emt',
        label: 'EMT staff/responder'
    },
];

const hfAdminRoleOptions = [
    {
        value: 'hfadmin',
        label: 'Health Facility Administrator'
    },
    {
        value: 'opcen',
        label: 'Primary OpCen User'
    },
    {
        value: 'emt',
        label: 'EMT staff/responder'
    },
];

const password = ref<any>(null);

const defaultFormValues: UserForm = {
    healthFacilityId: 0,
    firstName: '',
    lastName: '',
    status: 1
};

const userForm = ref(defaultFormValues);

const oldValue = ref(null);

const handleSubmit = async () => {
    try {
        if ((userForm.value.checkPassword || userForm.value.password) && (userForm.value.checkPassword !== userForm.value.password)) {
            notify({
                type: "error",
                title: "Error",
                text: "Passwords do not match."
            })
            if(password.value) password.value.focus();
            return;
        }
        if (mode.value === 'create') {
            await userStore.createUser(userForm.value, successCallback, errorCallback)
        } else {
            let toUpdate: any = {};
            let form = userForm.value;
            for (let i in form) {
                if (props.user[i] !== form[i]) {
                    toUpdate[i] = form[i];
                }
            }
            if (Object.keys(toUpdate).length > 0) {
                toUpdate.id = props.user.id;
                await userStore.updateUser(toUpdate, successCallback, errorCallback);
            } else {
                notify({
                    type: 'success',
                    text: 'Nothing to update.'
                });
                closeAndResetModal();
            }
        }
    } catch (error) {
        console.error(error);
        notify({
            type: "error",
            title: "Error",
            text: "An error has occurred. Please try again."
        })
    }
}

const closeAndResetModal = () => {
    userForm.value = {
        healthFacilityId: 0,
        firstName: '',
        lastName: '',
        status: 1
    };
    openModal.value = false;
}

const successCallback = (message) => {
    notify({
        type: 'success',
        title: 'Success',
        text: message
    })
    closeAndResetModal();
}

const errorCallback = (error) => {
    console.error(error);
}

const confirmDelete = async (id) => {
    const { isCanceled } = await confirm.reveal()

    if(isCanceled) return;
    
    deleteUser(id);
}

const deleteUser = async (id) => {
    await userStore.deleteUser(id, successCallback, errorCallback);
}

watch(openModal, (newVal) => {
    if (newVal) {
        if(mode.value === 'update') {
            userForm.value = {
                id: props.user.id,
                firstName: props.user.firstName,
                lastName: props.user.lastName,
                middleName: props.user.middleName,
                username: props.user.username,
                email: props.user.email,
                contactNo: props.user.contactNo,
                role: props.user.role,
                healthFacilityId: props.user.healthFacilityId,
                status: props.user.status
            };
        } else {
            userForm.value = {
                healthFacilityId: 0,
                firstName: '',
                lastName: '',
                status: 1
            };
            if (user?.value?.role !== 'superadmin') {
                userForm.value.healthFacilityId = user?.value?.healthFacilityId;   
            }
        }
    }
    
}, {deep: true});

onMounted(() => {
    if (user?.value?.role === 'superadmin') {
        facilityStore.getFacilityOptions();   
    }
})

</script>

<style scoped>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;

        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
