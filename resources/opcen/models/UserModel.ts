import { Facility } from "./FacilityModel";

export interface User {
    id: number;
    firstName: string;
    lastName: string;
    middleName?: string;
    username: string
    email: string;
    contactNo: number;
    contactNoFull: string;
    role: string;
    roleLabel: string;
    healthFacilityId?: number;
    healthFacilityName?: string;
    status: string|number;
    statusLabel:string;
}

export interface UserForm {
    id?: number;
    firstName: string;
    lastName: string;
    middleName?: string;
    username?: string
    email?: string;
    contactNo?: number;
    role?: string|number;
    healthFacilityId?: number;
    status: string|number;
    password?: string;
    checkPassword?: string;
}

export interface UserState {
    user?: User;
    userList: User[];
    loadingUserList: boolean;
    processingUser: boolean;
    isAuthenticated: boolean;
}

