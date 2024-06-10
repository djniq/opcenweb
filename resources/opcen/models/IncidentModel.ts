export interface IncidentState {
    incidents: Incident[];
    activeIncident?: any;
    ehrPatient: any;
    loadingIncidents: boolean;
    loadingEhr: boolean;
    processingIncident: boolean;
}

export interface Incident {
    id?: number;
    healthFacilityId?: number;
    reportedDatetime?: string;
    nature: string;
    natureLabel?: string;
    category: string;
    categoryLabel?: string;
    vicinity: string;
    vicinityLabel?: string;
    fromHealthFacilityId?: number|'self';
    from_health_facility?: any;
    toHealthFacilityId?: number|'self';
    to_health_facility?: any;
    origin: any|null;
    destination: any|null;
    status?: number;
    statusLabel?: string;
    patientEhrId?: string;
    patientFirstName: string;
    patientLastName: string;
    patientMiddleName?: string;
    patientBirthdate: string;
    patientBirthdateIsFortuitous?: boolean;
    patientAddress: string;
    chiefComplaint: string;
    remarks?: string;
}