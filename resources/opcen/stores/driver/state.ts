import { DriverState } from "@/models/DriverModel";

export const state = () :DriverState => {
    return {
        drivers: [],
        driverOptions: [],
        loadingDrivers: false,
        loadingDriverOptions: false,
        processingDriver: false
    }
}