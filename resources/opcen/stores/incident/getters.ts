import { IncidentState } from '@/models/IncidentModel';

export const getters = {
    getIncidents(state: IncidentState) {
        return state.incidents;
    }
}