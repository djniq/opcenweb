import { UserState } from '@/models/UserModel';

export const getters = {
    getUser(state: UserState) {
        return state.user
    },

    getUserList(state: UserState) {
        return state.userList
    }
}