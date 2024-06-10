<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CrewMemberResource;
use App\Models\CrewMember;
use App\Models\CrewSettings;
use App\Models\User;
use Illuminate\Http\Request;

class CrewMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $squadId 
     * @return \Illuminate\Http\Response
     */
    public function index(int $squadId)
    {
        $squad = CrewSettings::find($squadId);
        $crewMembers = CrewMember::where('crew_settings_id', $squadId)
            ->with('user:id,first_name,last_name,middle_name')
            ->get();
        $unassignedEmts = User::where('status', 1)
            ->where('health_facility_id', $squad->health_facility_id)
            ->where('role', 'emt')
            ->doesntHave('crewMember')
            ->get();
            
        return CrewMemberResource::collection([
            "crewMembers" => $crewMembers,
            "unassignedEmts" => $unassignedEmts
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
            $crewMember = CrewMember::updateOrCreate([
                "user_id" => $request->emtId
            ],[
                'crew_settings_id' => $request->squadId,
                'created_by' => Auth()->user()->id
            ]);

            $success = true;
            $message = 'Squad member added';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $crewMember ?? null
        ];

        return new CrewMemberResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrewMember  $crewMember
     * @return \Illuminate\Http\Response
     */
    public function show(CrewMember $crewMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CrewMember  $crewMember
     * @return \Illuminate\Http\Response
     */
    public function edit(CrewMember $crewMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CrewMember  $crewMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrewMember $crewMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $crewMemberId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $crewMemberId)
    {
        $crewMember = CrewMember::where('user_id', $crewMemberId);
        $crewMember->delete();

        return response(null, 204);
    }
}
