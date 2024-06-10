<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $driversQuery = Driver::select([
            "id",
            "health_facility_id",
            "first_name as firstName",
            "last_name as lastName",
            "middle_name as middleName",
            "status",
            "created_at as createdAt",
            "created_by as createdBy"
        ])->with('healthFacility:id,hf_name');
        if (Auth()->user()->role !== 'superadmin') {
            $driversQuery->where('health_facility_id', Auth()->user()->health_facility_id);
        }
        $drivers = $driversQuery->get();
        $drivers = $drivers->each(function($item, $key){
            $item->statusLabel = $item->getStatus($item->status);
            $item->healthFacilityId = $item->healthFacility->id;
            unset($item->health_facility_id);
            $item->healthFacilityName = $item->healthFacility->hf_name;
            unset($item->healthFacility);
        });
        return DriverResource::collection($drivers);
    }

    public function assignableDrivers(int $facilityId) {
        $drivers = Driver::select([
            "id",
            "health_facility_id",
            "first_name as firstName",
            "last_name as lastName",
            "middle_name as middleName",
            "status",
            "created_at as createdAt",
            "created_by as createdBy"
        ])
            ->where('health_facility_id', $facilityId)
            ->where('status', 1)
            ->whereDoesntHave('dispatch', function ($query) {
                return $query->where('status', '!=', 'completed');
            })
            ->get();
        return DriverResource::collection($drivers);
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
            $driver = Driver::create([
                "health_facility_id" => $request->healthFacilityId,
                "first_name" => $request->firstName,
                "last_name" => $request->lastName,
                "middle_name" => $request->middleName,
                "status" => $request->status,
                "created_by" => Auth()->user()->id
            ]);

            $success = true;
            $message = 'New driver record created';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $driver ?? null
        ];

        return new DriverResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $driverId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $driverId)
    {
        $driver = driver::find($driverId);
        try {
            $driver->update([
                "health_facility_id" => $request->healthFacilityId,
                "first_name" => $request->firstName,
                "last_name" => $request->lastName,
                "middle_name" => $request->middleName,
                "status" => $request->status,
                "updated_by" => Auth()->user()->id
            ]);

            $success = true;
            $message = 'driver record updated';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message,
            "data" => $driver ?? null
        ];
        
        return new DriverResource($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $driverId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $driverId)
    {
        $driver = Driver::find($driverId);
        $driver->delete();

        return response(null, 204);
    }
}
