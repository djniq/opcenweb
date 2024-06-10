import { UserState } from "@/models/UserModel";

export const state = () :UserState => {
    return {
        user: undefined,
        userList: [],
        loadingUserList: false,
        processingUser: false,
        isAuthenticated: false
    }
}