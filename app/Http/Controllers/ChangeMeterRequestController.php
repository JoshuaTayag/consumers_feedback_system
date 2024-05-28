<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
use DB;

class ChangeMeterRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cm_requests = ChangeMeterRequest::orderBy('id','asc')->paginate(9);
        $ref_employees = DB::table('ref_employees')
        ->select(DB::raw("CONCAT(last_name, ', ', SUBSTRING(first_name, 1, 1), '. ', SUBSTRING(middle_name, 1, 1)) AS full_name"))
        ->where('department', 'TSD')
        ->orderBy('last_name', 'ASC')
        ->get();
        return view('service_connect_order.change_meter.index',compact('cm_requests', 'ref_employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $municipalities = DB::connection('sqlSrvMembership')
        ->table('municipalities')
        ->select('*')
        ->orderBy('municipality_name', 'asc')
        ->get();

        $consumer_types = DB::connection('sqlSrvHousewiring')
        ->table('consumer_types')
        ->select('*')
        ->orderBy('name_type', 'asc')
        ->get();

        $occupancy_types = DB::connection('sqlSrvHousewiring')
        ->table('occupancy_types')
        ->select('*')
        ->orderBy('occupancy_name', 'asc')
        ->get();

        $type_of_meters = DB::connection('sqlSrvHousewiring')
        ->table('khw_meter_types')
        ->select('*')
        ->orderBy('meter_code', 'asc')
        ->get();
        
        // dd($districts);
        return view('service_connect_order.change_meter.create')->with(compact( 'municipalities', 'consumer_types', 'occupancy_types', 'type_of_meters'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
