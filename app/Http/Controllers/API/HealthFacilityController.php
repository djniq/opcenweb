<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHealthFacilityRequest;
use App\Http\Requests\UpdateHealthFacilityRequest;
use App\Http\Resources\HealthFacilityResource;
use App\Models\Ambulance;
use App\Models\Driver;
use App\Models\HealthFacility;
use App\Models\User;

class HealthFacilityController extends Controller
{
    public function index() {
        $facilities = HealthFacility::select([
            'id',
            'hf_name as name',
            'hf_email as email',
            'hf_contact_no as contactNo',
            'hf_level as level',
            'hf_location as location',
            'created_by as createdBy',
            'created_at as createdAt',
            'updated_by as updatedBy',
            'updated_at as updatedAt'
        ])->get();
        $facilities = $facilities->each(function($item, $key){
            $item->address = json_decode($item->location);
        });
        return HealthFacilityResource::collection($facilities);
    }

    public function assignablehealthFacilities() {
        $facilities = HealthFacility::select(['id', 'hf_name as name'])
            ->where('status', 1)
            ->get();
        return HealthFacilityResource::collection($facilities);
    }

    /**
     * Retrieve the asset counters for all or per healthFacility
     */
    public function counters() {
        $facilityCountQuery = HealthFacility::where('status', 1);
        $ambulanceCountQuery = Ambulance::where('status', 1);
        $driverCountQuery = Driver::where('status', 1);
        $responderCountQuery = User::where('role', 'emt')->where('status', 1);
        if (Auth()->user()->role !== 'superadmin') {
            $facilityCountQuery->where('id', Auth()->user()->health_facility_id);
            $ambulanceCountQuery->where('health_facility_id', Auth()->user()->health_facility_id);
            $driverCountQuery->where('health_facility_id', Auth()->user()->health_facility_id);
            $responderCountQuery->where('health_facility_id', Auth()->user()->health_facility_id);
        }
        $facilityCount = $facilityCountQuery->count();
        $ambulanceCount = $ambulanceCountQuery->count();
        $driverCount = $driverCountQuery->count();
        $responderCount = $responderCountQuery->count();

        return response()->json([
            "facilities" => $facilityCount,
            "ambulances" => $ambulanceCount,
            "drivers" => $driverCount,
            "responders" => $responderCount
        ]);
    }

    /**
     * Retrieve the asset counters for per health facility
     */
    public function countersPerFacility(int $healthFacility) {
        $ambulanceCount = Ambulance::where('health_facility_id', $healthFacility)->where('status', 1)->count();
        $driverCount = Driver::where('health_facility_id', $healthFacility)->where('status', 1)->count();
        $responderCount = User::where('health_facility_id', $healthFacility)->where('role', 'emt')->where('status', 1)->count();
        
        return HealthFacilityResource::collection([
            "facilities" => 1,
            "ambulances" => $ambulanceCount,
            "drivers" => $driverCount,
            "responders" => $responderCount
        ]);
    }

    /**
     * Create new health facility
     */
    public function store(StoreHealthFacilityRequest $request)
    {
        try {
            $healthFacility = HealthFacility::create([
                "hf_name" => $request->name,
                "hf_email" => $request->email,
                "hf_contact_no" => $request->contactNo,
                "hf_level" => $request->level,
                "hf_location" => json_encode($request->address),
                "status" => 1,
                "created_by" => Auth()->user()->id
            ]);

            $success = true;
            $message = 'New Health Facility Created';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        // return response()->json($response);
        return new HealthFacilityResource($healthFacility);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(HealthFacility::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\StoreHealthFacilityRequest  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHealthFacilityRequest $request, $id)
    {
        $facility = HealthFacility::find($id);
        try {
            $facility->update([
                "hf_name" => $request->name,
                "hf_email" => $request->email,
                "hf_contact_no" => $request->contactNo,
                "hf_level" => $request->level,
                "hf_location" => json_encode($request->address),
                "status" => 1,
                "updated_by" => Auth()->user()->id
            ]);

            $success = true;
            $message = 'Health Facility Updated';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }
        
        return new HealthFacilityResource($facility);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $facility = HealthFacility::find($id);
        $facility->delete();

        return response(null, 204);
    }
}
