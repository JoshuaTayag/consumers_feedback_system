<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceConnectOrder;
use DB;

class ServiceConnectOrderController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:service-connect-order-list|service-connect-order-create|service-connect-order-edit|service-connect-order-delete', ['only' => ['index','store']]);
         $this->middleware('permission:service-connect-order-create', ['only' => ['create','store']]);
         $this->middleware('permission:service-connect-order-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:service-connect-order-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scos = ServiceConnectOrder::orderBy('application_id','asc')->where('SCONo', 'like', 'h%')->orderBy('SCONo', 'asc')->paginate(12);
        return view('service_connect_order.index',compact('scos'));
    }

    public function indexCM()
    {
        $scos = ServiceConnectOrder::orderBy('SCONo','DESC')->where('SCONo', 'like', 'h%')->paginate(12);
        return view('service_connect_order.change_meter.index',compact('scos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();

        // dd($districts);
        return view('service_connect_order.create')->with(compact('districts'));
    }

    public function createCM()
    {
        $sitios = DB::connection('sqlSrvMembership')
        ->table('Sitio Table')
        ->select('*')
        ->orderBy('Sitio', 'asc')
        ->get();

        $barangays = DB::connection('sqlSrvMembership')
        ->table('Barangay Table')
        ->select('*')
        ->orderBy('Brgy', 'asc')
        ->get();

        $municipalities = DB::connection('sqlSrvMembership')
        ->table('Municipality Table')
        ->select('*')
        ->orderBy('Municipality', 'asc')
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
        return view('service_connect_order.change_meter.create')->with(compact('barangays', 'sitios', 'municipalities', 'consumer_types', 'occupancy_types', 'type_of_meters'));
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
