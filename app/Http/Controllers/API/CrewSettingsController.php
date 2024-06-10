<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CrewSettingsResource;
use App\Models\CrewSettings;
use Illuminate\Http\Request;

class CrewSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crewSettingsQuery = CrewSettings::select([
            "id",
            "health_facility_id",
            "squad_name as squadName",
            "status",
            "created_at as createdAt",
            "created_by as createdBy"
        ])
        ->with('healthFacility:id,hf_name')
        // ->with('crewMembers:id, user_id')
        ->orderBy('createdAt', 'DESC');
        if (Auth()->user()->role !== 'superadmin') {
            $crewSettingsQuery->where('health_facility_id', Auth()->user()->health_facility_id);
        }
        $crewSettings = $crewSettingsQuery->get();
        $crewSettings = $crewSettings->each(function($item, $key){
            $item->statusLabel = $item->getStatus($item->status);
            $item->healthFacilityId = $item->healthFacility->id;
            unset($item->health_facility_id);
            $item->healthFacilityName = $item->healthFacility->hf_name;
            unset($item->healthFacility);
        });
        
        return CrewSettingsResource::collection($crewSettings);
    }

    public function assignableSquads(int $facilityId) {
        $squads = CrewSettings::select([
            "id",
            "health_facility_id",
            "squad_name as squadName",
            "status",
            "created_at as createdAt",
            "created_by as createdBy"
        ])
            ->where('health_facility_id', $facilityId)
            ->where('status', 1)
            ->whereHas('crewMembers')
            ->get();
        return CrewSettingsResource::collection($squads);
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
            $squad = CrewSettings::create([
                "health_facility_id" => $request->healthFacilityId,
                "squad_name" => $request->squadName,
                "status" => 1,
                "created_by" => Auth()->user()->id
            ]);

            $success = true;
            $message = 'New squad created';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $squad ?? null
        ];

        return new CrewSettingsResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrewSettings  $crewSettings
     * @return \Illuminate\Http\Response
     */
    public function show(CrewSettings $crewSettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CrewSettings  $crewSettings
     * @return \Illuminate\Http\Response
     */
    public function edit(CrewSettings $crewSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $crewSettingsId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $crewSettingsId)
    {
        $squad = CrewSettings::find($crewSettingsId);
        try {
            $squad->update($request->all());

            $success = true;
            $message = 'Squad record updated';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message,
            "data" => $squad ?? null
        ];
        
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $crewSettingsId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $crewSettingsId)
    {
        $squad = CrewSettings::find($crewSettingsId);
        $squad->delete();

        return response(null, 204);
    }
}
