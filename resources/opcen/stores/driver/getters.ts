import { DriverState } from '@/models/DriverModel';

export const getters = {
    getDrivers(state: DriverState) {
        return state.drivers;
    }
}