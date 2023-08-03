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
        $scos = ServiceConnectOrder::orderBy('id','DESC')->paginate(10);
        return view('service_connect_order.index',compact('scos'));
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
