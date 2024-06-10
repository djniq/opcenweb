import { FacilityState } from '@/models/FacilityModel';

export const getters = {
    getFacilities(state: FacilityState) {
        return state.facilities;
    }
}