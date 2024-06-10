<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceResource;
use App\Models\Ambulance;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class AmbulanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ambulancesQuery = Ambulance::select([
            "id",
            "health_facility_id",
            "amb_type as type",
            "amb_plate_no as plateNo",
            "status",
            "created_at as createdAt",
            "created_by as createdBy"
        ])->orderBy('created_at', 'DESC')->with('healthFacility:id,hf_name');
        $ambulances = $ambulancesQuery->get();
        if (Auth()->user()->role !== 'superadmin') {
            $ambulancesQuery->where('health_facility_id', Auth()->user()->health_facility_id);
        }
        $ambulances = $ambulances->each(function($item, $key){
            $item->statusLabel = $item->getStatus($item->status);
            $item->healthFacilityId = $item->healthFacility->id;
            unset($item->health_facility_id);
            $item->healthFacilityName = $item->healthFacility->hf_name;
            unset($item->healthFacility);
        });
        return AmbulanceResource::collection($ambulances);
    }

    public function assignableAmbulances(int $facilityId) {
        $ambulances = Ambulance::select(['id', 'amb_plate_no as plateNo'])
            ->where('health_facility_id', $facilityId)
            ->where('status', 1)
            ->whereDoesntHave('dispatch', function ($query) {
                return $query->where('status', '!=', 'completed');
            })
            ->get();
        return AmbulanceResource::collection($ambulances);
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
            $ambulance = Ambulance::create([
                "health_facility_id" => $request->healthFacilityId,
                "amb_type" => $request->type,
                "amb_plate_no" => $request->plateNo,
                "status" => $request->status,
                "created_by" => Auth()->user()->id
            ]);

            $success = true;
            $message = 'New Ambulance Created';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $ambulance ?? null
        ];

        return new AmbulanceResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ambulance  $ambulance
     * @return \Illuminate\Http\Response
     */
    public function show(Ambulance $ambulance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $ambulance = Ambulance::find($id);
        try {
            $ambulance->update([
                "health_facility_id" => $request->healthFacilityId,
                "amb_type" => $request->type,
                "amb_plate_no" => $request->plateNo,
                "status" => $request->status,
                "updated_by" => Auth()->user()->id
            ]);

            $success = true;
            $message = 'Ambulance record updated';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message,
            "data" => $ambulance ?? null
        ];
        
        return new AmbulanceResource($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $facility = Ambulance::find($id);
        $facility->delete();

        return response(null, 204);
    }
}
