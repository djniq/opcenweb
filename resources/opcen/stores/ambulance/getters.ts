import { AmbulanceState } from '@/models/AmbulanceModel';

export const getters = {
    getDrivers(state: AmbulanceState) {
        return state.ambulances;
    }
}