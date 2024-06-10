export interface DriverState {
    drivers: Driver[];
    driverOptions: Driver[];
    loadingDrivers: boolean;
    loadingDriverOptions: boolean;
    processingDriver: boolean;
}

export interface Driver {
    id?: number;
    healthFacilityId?: number;
    healthFacilityName?: string;
    firstName?: string;
    lastName?: string;
    middleName?: string|null;
    status?: number;
    statusLabel?: string;
}

