import { IncidentState } from "@/models/IncidentModel";

export const state = () :IncidentState => {
    return {
        incidents: [],
        activeIncident: null,
        ehrPatient: null,
        loadingIncidents: false,
        loadingEhr: false,
        processingIncident: false
    }
}