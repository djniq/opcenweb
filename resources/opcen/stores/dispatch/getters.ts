import { DispatchState } from '@/models/DispatchModel';

export const getters = {
    getDispatches(state: DispatchState) {
        return state.dispatches;
    }
}