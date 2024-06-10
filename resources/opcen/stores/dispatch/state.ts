import { DispatchState } from "@/models/DispatchModel";

export const state = () : DispatchState => {
    return {
        dispatches: [],
        dispatchCounters: null,
        emtDispatches: null,
        dispatchReports: null,
        dispatchReportsCounters: null,
        activeDispatch: null,
        loadingDispatches: false,
        loadingDispatchCounters: false,
        loadingActiveDispatch: false,
        loadingReports: false,
        loadingReportsCounters: false,
        processingDispatch: false,
    }
}