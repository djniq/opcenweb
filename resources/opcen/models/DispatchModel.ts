export interface DispatchState {
    dispatches: any[];
    activeDispatch: any;
    dispatchReportsCounters: any;
    dispatchCounters: any;
    dispatchReports: any;
    emtDispatches: any;
    loadingDispatches: boolean;
    loadingDispatchCounters: boolean;
    loadingActiveDispatch: boolean;
    loadingReports: boolean;
    loadingReportsCounters: boolean;
    processingDispatch: boolean;
}

export interface Dispatch {
    id?: number;
}

export var dispatchStatusLabel = {
    'pending' : "Pending",
    'start' : 'Start',
    'arrived-on-site' : "On Site",
    'moved-out-from-site' : "Moving Out",
    'arrived-at-destination' : "At Destination",
    'endorsed-patient' : "Endorse Patient",
    'completed' : "Complete"
}

export var dispatchStatusIndex = {
    'pending': 0,
    'start': 1,
    'arrived-on-site': 2,
    'moved-out-from-site': 3,
    'arrived-at-destination': 4,
    'endorsed-patient': 5,
    'completed': 6
}

export var dispatchStatusIndexValue = {
    0: 'pending',
    1: 'start',
    2: 'arrived-on-site',
    3: 'moved-out-from-site',
    4: 'arrived-at-destination',
    5: 'endorsed-patient',
    6: 'completed'
}
