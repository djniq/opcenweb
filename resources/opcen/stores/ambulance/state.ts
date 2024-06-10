import { AmbulanceState } from "@/models/AmbulanceModel";

export const state = () :AmbulanceState => {
    return {
        ambulances: [],
        ambulanceOptions: [],
        loadingAmbulances: false,
        processingAmbulance: false,
        loadingAmbulanceOptions: false
    }
}