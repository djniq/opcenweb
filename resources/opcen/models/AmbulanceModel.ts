export interface AmbulanceState {
    ambulances: Ambulance[];
    ambulanceOptions: Ambulance[];
    loadingAmbulances: boolean;
    processingAmbulance: boolean;
    loadingAmbulanceOptions: boolean;
}

export interface Ambulance {
    id?: number;
    healthFacilityId?: number;
    healthFacilityName?: string;
    type?: string;
    plateNo?: string;
    status?: number;
    statusLabel?: string;
}

