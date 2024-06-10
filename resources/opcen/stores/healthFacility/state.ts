import { FacilityState } from "@/models/FacilityModel";

export const state = () :FacilityState => {
    return {
        facilities: [],
        assetCounters: {
            facilities: 0,
            ambulances: 0,
            responders: 0,
            drivers: 0
        },
        facilityOptions: [],
        loadingFacilities: false,
        loadingCounters: false,
        loadingOptions: false,
        processingFacility: false,
    }
}