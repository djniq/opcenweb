<?php

namespace App\Http\Controllers\API;

use App\Events\DispatchCreated as EventsDispatchCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\DispatchResource;
use App\Models\Ambulance;
use App\Models\Crew;
use App\Models\CrewMember;
use App\Models\CrewSettings;
use App\Models\Dispatch;
use App\Models\DispatchStatusChangeLog;
use App\Models\Incident;
use App\Models\User;
use App\Notifications\DispatchCreated;
use App\Service\ExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dispatches = Dispatch::select([
            "id",
            "incident_id",
            "driver_id",
            "ambulance_id",
            "crew_settings_id",
            "tagged_ambulance_datetime",
            "start_datetime",
            "arrived_on_site_datetime",
            "moved_out_from_site_datetime",
            "arrived_to_destination_datetime",
            "endorsed_patient_datetime",
            "completed_datetime",
            "remarks",
            "status",
            "updated_by",
            "created_by"
        ])
        ->with(['incident', 'ambulance', 'driver', 'crews.user', 'activeSquad'])
        ->orderBy('created_at', 'DESC')
        ->orderBy('status', 'ASC')
        ->get();

        $dispatches = $dispatches->each(function($item, $key){
            $item->statusLabel = $item->getStatus($item->status);
        });
        
        return DispatchResource::collection($dispatches);
    }

    public function getActiveDispatch() {
        $crewData = Crew::select('user_id', 'dispatch_id')
            ->where('user_id', Auth()->user()->id)
            ->whereHas('dispatch', function ($query) {
                $query->where('status', '<>', 'completed');
            })
            ->with(['dispatch.incident.fromHealthFacility', 'dispatch.incident.toHealthFacility', 'dispatch.ambulance', 'dispatch.driver'])
            ->get();
        
            $crewData = $crewData->each(function($item, $key){
                $item->dispatch->statusLabel = $item->dispatch->getStatus($item->dispatch->status);
                $item->dispatch->incident->natureLabel = $item->dispatch->incident->getNatureOfOperation($item->dispatch->incident->nature_of_operation);
                $item->dispatch->incident->categoryLabel = $item->dispatch->incident->getCategory($item->dispatch->incident->transfer_category);
                $item->dispatch->incident->vicinityLabel = $item->dispatch->incident->getVicinity($item->dispatch->incident->transfer_vicinity);
                $item->dispatch->incident->statusLabel = $item->dispatch->incident->getStatus($item->dispatch->incident->status);
                $item->dispatch->incident->origin = json_decode($item->dispatch->incident->origin);
                $item->dispatch->incident->destination = json_decode($item->dispatch->incident->destination);
            });
        return DispatchResource::collection($crewData);
    }

    public function getDispatchCounters() {
        $dispatches = Crew::select('user_id', 'dispatch_id')
            ->where('user_id', Auth()->user()->id)
            ->with('dispatch.incident')
            ->get();
        
        $emergencyCounter = 0;
        $responseCounter = 0;
        
        foreach ($dispatches as $key => $value) {
            $responseCounter++;
            if (in_array($value->dispatch->incident->nature_of_operation, ['emergency-dispatch-trauma', 'emergency-dispatch-medical'])) {
                $emergencyCounter++;
            }
        }
        return response()->json([
            "responses" => $responseCounter,
            "emergency" => $emergencyCounter
        ]);
    }

    public function getReportsCounters() {
        $conductionsQuery = Dispatch::where('status', 'completed');
        $totalAmbulanceCountQuery = Ambulance::where('status', 1);
        $availableAmbulanceCountQuery = Ambulance::where('status', 1)
        ->whereDoesntHave('dispatch', function ($query) {
            return $query->where('status', '!=', 'completed');
        });
        $averageConductionQuery = Dispatch::select(DB::raw('ROUND(AVG(TIMESTAMPDIFF(MINUTE, start_datetime, completed_datetime)), 0) as averageConduction'));
        $totalSquadCountQuery = CrewSettings::where('status','<>', 0);
        $availableSquadCountQuery = CrewSettings::where('status', 1);
        $totalEmergencyTraumaQuery = Incident::where('nature_of_operation', 'emergency-dispatch-trauma');
        $totalEmergencyMedicalQuery = Incident::where('nature_of_operation', 'emergency-dispatch-medical');
        $averageMovingOutQuery = Dispatch::select(DB::raw('ROUND(AVG(TIMESTAMPDIFF(MINUTE, arrived_on_site_datetime, moved_out_from_site_datetime)), 0) as averageMovingOut'));
        
        if (Auth()->user()->role !== 'superadmin') {
            $conductionsQuery->whereHas('incident', function ($query) {
                $query->where('health_facility_id', Auth()->user()->health_facility_id);
            });
            $totalAmbulanceCountQuery->where('health_facility_id', Auth()->user()->health_facility_id);
            $availableAmbulanceCountQuery->where('health_facility_id', Auth()->user()->health_facility_id);
            $averageConductionQuery->whereHas('incident', function ($query) {
                $query->where('health_facility_id', Auth()->user()->health_facility_id);
            });
            $totalSquadCountQuery->where('health_facility_id', Auth()->user()->health_facility_id);
            $availableSquadCountQuery->where('health_facility_id', Auth()->user()->health_facility_id);
            $totalEmergencyTraumaQuery->where('health_facility_id', Auth()->user()->health_facility_id);
            $totalEmergencyMedicalQuery->where('health_facility_id', Auth()->user()->health_facility_id);
            $averageMovingOutQuery->whereHas('incident', function ($query) {
                $query->where('health_facility_id', Auth()->user()->health_facility_id);
            });
        }
        $conductionsCount = $conductionsQuery->count();
        $totalAmbulanceCount = $totalAmbulanceCountQuery->count();
        $availableAmbulanceCount = $availableAmbulanceCountQuery->count();
        $averageConduction = $averageConductionQuery->first();
        $totalSquadCount = $totalSquadCountQuery->count();
        $availableSquadCount = $availableSquadCountQuery->count();
        $totalEmergencyTrauma = $totalEmergencyTraumaQuery->count();
        $totalEmergencyMedical = $totalEmergencyMedicalQuery->count();
        $averageMovingOut = $averageMovingOutQuery->first();

        return response()->json([
            "conductions" => $conductionsCount,
            "totalAmbulances" => $totalAmbulanceCount,
            "availableAmbulances" => $availableAmbulanceCount,
            "totalSquads" => $totalSquadCount,
            "availableSquads" => $availableSquadCount,
            "averageConductionMinute" => $averageConduction->averageConduction,
            "averageMovingOutMinute" => $averageMovingOut->averageMovingOut,
            "emergencyTraumaCount" => $totalEmergencyTrauma,
            "emergencyMedicalCount" => $totalEmergencyMedical,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $dispatch = new Dispatch();
            $dispatch->incident_id = $request->incidentId;
            $dispatch->driver_id = $request->driverId;
            $dispatch->ambulance_id = $request->ambulanceId;
            $dispatch->crew_settings_id = $request->squadId;
            $dispatch->tagged_ambulance_datetime = Carbon::now();
            $dispatch->status = 'pending';
            $dispatch->created_by = Auth()->user()->id;
            $dispatch->save();

            // Change the status of the incident to `dispatch-initiated`
            $incident = Incident::find($request->incidentId);
            $incident->status = 1;
            $incident->save();

            // Save the crew members
            $crewMembers = CrewMember::select('user_id')
                ->where('crew_settings_id', $request->squadId)
                ->get()
                ->toArray();
            $crewData = [];
            foreach ($crewMembers as $key => $member) {
                $crewData[] = [
                    "user_id" => $member['user_id'],
                    "dispatch_id" => $dispatch->id,
                    "created_by" => Auth()->user()->id
                ];
                $crewUser = User::find($member['user_id']);
                $crewUser->notify(new DispatchCreated($dispatch, Auth()->user()));
            }
            $saveCrews = Crew::insert($crewData);

            // Change the status of Squad to dispatched
            $squad = CrewSettings::find($request->squadId);
            $squad->status = 2;
            $squad->save();

            $success = true;
            $message = 'Dispatch Created';

            // Notify - disabled for now
            // broadcast(new EventsDispatchCreated($dispatch))->toOthers();
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function show(Dispatch $dispatch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function edit(Dispatch $dispatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $dispatchId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $dispatchId)
    {
        $dispatch = Dispatch::find($dispatchId);
        $status = $dispatch->status;
        $activeSquad = $dispatch->crew_settings_id;
        try {
            $dispatchForm = $request->all();
            $dispatchForm['updated_by'] = Auth()->user()->id;
            // Additional changes if status is changed
            if($request->status) {
                switch ($request->status) {
                    case 'start':
                        $dispatchForm['start_datetime'] = Carbon::now();
                        break;

                    case 'arrived-on-site':
                        $dispatchForm['arrived_on_site_datetime'] = Carbon::now();
                        break;
                    
                    case 'moved-out-from-site':
                        $dispatchForm['moved_out_from_site_datetime'] = Carbon::now();
                        break;
                        
                    case 'arrived-at-destination':
                        $dispatchForm['arrived_to_destination_datetime'] = Carbon::now();
                        break;

                    case 'endorsed-patient':
                        $dispatchForm['endorsed_patient_datetime'] = Carbon::now();
                        break;
                        
                    case 'completed':
                        $dispatchForm['completed_datetime'] = Carbon::now();
                        break;

                    default:
                        # code...
                        break;
                }
            }
            $dispatch->update($dispatchForm);

            if($request->status) {
                // log the changes
                $log = new DispatchStatusChangeLog();
                $log->dispatch_id = $dispatchId;
                $log->from_status = $status;
                $log->to_status = $request->status;
                $log->recorded_location = $request->last_recorded_location;
                $log->created_by = Auth()->user()->id;
                $log->save();

                // if completed, change the incident status as well
                if ($request->status === 'completed') {
                    $incident = Incident::find($dispatch->incident_id);
                    $incident->status = 2;
                    $incident->save();

                    // Set the active squad to available after completion
                    $crewSetting = CrewSettings::find($activeSquad);
                    $crewSetting->status = 1;
                    $crewSetting->save();
                }
            }

            $success = true;
            $message = 'Dispatch record updated';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message,
            "data" => $dispatch ?? null
        ];
        
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $dispatchId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $dispatchId)
    {
        $dispatch = Dispatch::find($dispatchId);
        $dispatch->delete();

        return response(null, 204);
    }

    public function reports(Request $request) {
        return DispatchResource::collection($this->generateReport(null, $request->query('dateRange')));
    }

    public function emtDispatches() {
        return DispatchResource::collection($this->generateReport(Auth()->user()->id));
    }

    public function export(Request $request) {
        // Build export data
        $report = $this->generateReport(null, $request->query('dateRange'));        

        $exportService = new ExportService();
        return $exportService->export('dispatch_report.csv', $report['tableHeaders'], $report['content']);
    }

    private function generateReport($responderId = null, $dateRange = null) {
        $dispatchesQuery = Dispatch::with([
            'incident.healthFacility',
            'incident.fromHealthFacility',
            'incident.toHealthFacility',
            'ambulance',
            'driver',
            'crews.user'])
            ->orderBy('created_at', 'DESC');
        if ($responderId) {
            $dispatchesQuery->where('status', 'completed')
                ->whereHas('crews', function($query) use ($responderId) {
                    $query->where('user_id', '=', $responderId);
                });
        }
        if ($dateRange) {
            $dispatchesQuery->whereHas('incident', function($query) use ($dateRange) {
                $query->whereBetween('created_at', [Carbon::parse($dateRange[0]), Carbon::parse($dateRange[1])]);
            });
        }
        $dispatches = $dispatchesQuery->get();
            
        $tableHeaders = [
            'Incident ID',
            'Health Facility',
            'Nature of Operation',
            'Transport Category',
            'Vicinity',
            'Origin',
            'Destination',
            'Patient EHR ID',
            "Patient's Name",
            "Patient's Address",
            "Chief Complaint",
            "Remarks",
            "Dispatch ID",
            "Ambulance Plate No.",
            "Ambulance Type",
            "Responders",
            "Tagged Ambulance",
            "Dispatch Started",
            "Arrived On Site",
            "Moved Out From Site",
            "Arrived at Destination",
            "EndorsedPatient",
            "Completed",
            "Last Recorded Dispatch Status"
        ];
        $content = [];
        
        foreach ($dispatches as $key => $d) {
            $crewMembers = [];
            foreach ($d->crews as $key => $crew) {
            
                $crewMembers[] = $crew->user->last_name . ', ' . $crew->user->first_name . ' ' . $crew->user->  middle_name;
            }
            $content[] = [
                $d->incident->id,
                $d->incident->healthFacility->hf_name,
                $d->incident->getNatureOfOperation($d->incident->nature_of_operation),
                $d->incident->getCategory($d->incident->transfer_category),
                $d->incident->getVicinity($d->incident->transfer_vicinity),
                $d->incident->from_health_facility_id ? $d->incident->fromHealthFacility->hf_name : json_decode($d->incident->origin, true)['formatted_address'],
                $d->incident->to_health_facility_id ? $d->incident->toHealthFacility->hf_name : json_decode($d->incident->destination, true)['formatted_address'],
                $d->incident->patient_ehr_id,
                $d->incident->patient_last_name . ', ' . $d->incident->patient_first_name . ' ' . $d->incident->patient_middle_name,
                $d->incident->patient_address,
                $d->incident->chief_complaint,
                $d->incident->remarks,
                $d->id,
                $d->ambulance->amb_plate_no,
                $d->ambulance->amb_type,
                implode("\n", $crewMembers),
                $d->tagged_ambulance_datetime ? Carbon::parse($d->tagged_ambulance_datetime)->tz('Asia/Manila')->format('M d, Y H:i:s') : '',
                $d->start_datetime ? Carbon::parse($d->start_datetime)->tz('Asia/Manila')->format('M d, Y H:i:s') : '',
                $d->arrived_on_site_datetime ? Carbon::parse($d->arrived_on_site_datetime)->tz('Asia/Manila')->format('M d, Y H:i:s') : '',
                $d->moved_out_from_site_datetime ? Carbon::parse($d->moved_out_from_site_datetime)->tz('Asia/Manila')->format('M d, Y H:i:s') : '',
                $d->arrived_to_destination_datetime ? Carbon::parse($d->arrived_to_destination_datetime)->tz('Asia/Manila')->format('M d, Y H:i:s') : '',
                $d->endorsed_patient_datetime ? Carbon::parse($d->endorsed_patient_datetime)->tz('Asia/Manila')->format('M d, Y H:i:s') : '',
                $d->completed_datetime ? Carbon::parse($d->completed_datetime)->tz('Asia/Manila')->format('M d, Y H:i:s') : '',
                $d->getStatus($d->status)
            ];
        }
        return [
            "tableHeaders" => $tableHeaders,
            "content" => $content
        ];
    }
}
