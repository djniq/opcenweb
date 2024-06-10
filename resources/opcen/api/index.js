import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api',
    withCredentials: true,
});

const userUrl = '/user';
const healthFacilityUrl = '/health-facility';
const ambulanceUrl = '/ambulance';
const driverUrl = '/driver';
const incidentUrl = '/incident';
const squadUrl = '/squad';
const crewUrl = '/crew';
const dispatchUrl = '/dispatch';
const notifUrl = '/notifications';

const formatDataKey = key => key.replace(/[A-Z]/g, letter => `_${letter.toLowerCase()}`);

const formatApiData = (data) => {
    let apiData = {};
    for(let i in data) {
        if (i === 'id') continue;
        apiData[formatDataKey(i)] = data[i];
    }
    return apiData;
}

export default {
    // User
    getUser() {
        return apiClient.get(userUrl);
    },
    getUsers() {
        return apiClient.get(`${userUrl}/list`);
    },
    createUser(data) {
        return apiClient.post(userUrl, data);
    },
    updateUser(data) {
        let apiData = formatApiData(data);
        return apiClient.put(`${userUrl}/${data.id}`, apiData);
    },
    deleteUser(id) {
        return apiClient.delete(`${userUrl}/${id}`)
    },

    // Health Facility
    getFacilities() {
        return apiClient.get(healthFacilityUrl);
    },
    getFacilityAssetCounters() {
        return apiClient.get(`${healthFacilityUrl}/counters`);
    },
    getFacilityAssetCountersEach(facilityId) {
        return apiClient.get(`${healthFacilityUrl}/counters/${facilityId}`);
    },
    getFacilityOptions() {
        return apiClient.get(`${healthFacilityUrl}/options`);
    },
    createFacility(data) {
        return apiClient.post(healthFacilityUrl, data);
    },
    updateFacility(data) {
        return apiClient.put(`${healthFacilityUrl}/${data.id}`, data);
    },
    deleteFacility(id) {
        return apiClient.delete(`${healthFacilityUrl}/${id}`)
    },

    // Ambulances
    getAmbulances() {
        return apiClient.get(ambulanceUrl);
    },
    getAmbulanceOptions(facilityId) {
        return apiClient.get(`${ambulanceUrl}/options/${facilityId}`);
    },
    createAmbulance(data) {
        return apiClient.post(ambulanceUrl, data);
    },
    updateAmbulance(data) {
        return apiClient.put(`${ambulanceUrl}/${data.id}`, data);
    },
    deleteAmbulance(id) {
        return apiClient.delete(`${ambulanceUrl}/${id}`)
    },

    // Drivers
    getDrivers() {
        return apiClient.get(driverUrl);
    },
    getDriverOptions(facilityId) {
        return apiClient.get(`${driverUrl}/options/${facilityId}`);
    },
    createDriver(data) {
        return apiClient.post(driverUrl, data);
    },
    updateDriver(data) {
        return apiClient.put(`${driverUrl}/${data.id}`, data);
    },
    deleteDriver(id) {
        return apiClient.delete(`${driverUrl}/${id}`)
    },

    // Squad
    getSquads() {
        return apiClient.get(squadUrl);
    },
    getSquadOptions(facilityId) {
        return apiClient.get(`${squadUrl}/options/${facilityId}`);
    },
    createSquad(data) {
        return apiClient.post(squadUrl, data);
    },
    updateSquad(data) {
        let apiData = formatApiData(data);
        return apiClient.put(`${squadUrl}/${data.id}`, apiData);
    },
    deleteSquad(id) {
        return apiClient.delete(`${squadUrl}/${id}`)
    },

    // Incident
    getIncidents(dateRange) {
        return apiClient.get(incidentUrl, {params: {dateRange: dateRange}});
    },
    getIncidentDetails(id) {
        return apiClient.get(`${incidentUrl}/${id}`);
    },
    createIncident(data) {
        return apiClient.post(incidentUrl, data);
    },
    updateIncident(data) {
        let apiData = formatApiData(data);
        return apiClient.put(`${incidentUrl}/${data.id}`, apiData);
    },
    deleteIncident(id) {
        return apiClient.delete(`${incidentUrl}/${id}`)
    },

    // Squad Crew
    getCrewMembers(squadId) {
        return apiClient.get(`${crewUrl}/${squadId}`);
    },
    addCrewMember(data) {
        return apiClient.post(crewUrl, data);
    },
    removeCrewMember(emtId) {
        return apiClient.delete(`${crewUrl}/${emtId}`)
    },

    // Dispatchs
    getDispatches() {
        return apiClient.get(dispatchUrl);
    },
    loadActiveDispatch() {
        return apiClient.get(`${dispatchUrl}/active-dispatch`)
    },
    loadDispatchCounters() {
        return apiClient.get(`${dispatchUrl}/counters`)
    },
    loadDispatchReports(dateRange) {
        return apiClient.get(`${dispatchUrl}/reports`, {params: {dateRange: dateRange}})
    },
    loadDispatchReportsCounters() {
        return apiClient.get(`${dispatchUrl}/reports/counters`)
    },
    loadEmtDispatches() {
        return apiClient.get(`${dispatchUrl}/emt-dispatches`)
    },
    exportDispatchReport(dateRange) {
        return apiClient.get(`${dispatchUrl}/export`, {params: {dateRange: dateRange}})
    },
    createDispatch(data) {
        return apiClient.post(dispatchUrl, data);
    },
    updateDispatch(data) {
        return apiClient.put(`${dispatchUrl}/${data.id}`, data);
    },
    deleteDispatch(id) {
        return apiClient.delete(`${driverUrl}/${id}`)
    },

    // Notifications
    getNotifications() {
        return apiClient.get(notifUrl);
    },
    getUnreadNotifications() {
        return apiClient.get(`${notifUrl}/unread`);
    },
    markAsRead() {
        return apiClient.post(`${notifUrl}/mark-as-read`);
    }
};