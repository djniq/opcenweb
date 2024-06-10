export interface FacilityState {
    facilities: Facility[];
    assetCounters: FacilityAssetCounters;
    facilityOptions: FacilityOption[];
    loadingFacilities: boolean;
    loadingCounters: boolean;
    loadingOptions: boolean;
    processingFacility: boolean;
}

export interface Facility {
    id?: number;
    name?: string;
    address?: google.maps.Map;
    email?: string;
    contactNo?: string;
    status?: number;
    level?: string;
}

export interface FacilityOption {
    id: number;
    name: string;
}

export interface FacilityAssetCounters {
    facilities: number;
    ambulances: number;
    drivers: number;
    responders: number;
}

export const FacilityLevel = {
    primary: 'Primary',
    secondary: 'Secondary',
    tertiary: 'Tertiary',
    specialized: 'Specialized'
    
}
