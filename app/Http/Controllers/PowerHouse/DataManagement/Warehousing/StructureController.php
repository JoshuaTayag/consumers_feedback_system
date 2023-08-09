<?php

namespace App\Http\Controllers\PowerHouse\DataManagement\Warehousing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataManagement\Structure;
use App\Models\DataManagement\StructureItem;
use DB;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structures = Structure::with('structure_items')->orderBy('id','DESC')->paginate(10);
        // dd($structures);
        return view('power_house.warehousing.data_management.structure_index',compact('structures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('power_house.warehousing.data_management.structure_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate requests
        $this->validate($request, [
            'structure_code' => ['required', 'string', 'max:255', 'unique:structures'],
            'status' => ['required']
        ]);

        // Insert Record structure
        $structure = Structure::create([
            "structure_code" => $request->structure_code,
            "remarks" => $request->remarks,
            "status" => $request->status,
        ]);


        foreach($request->items as $item){ // $interests array contains input data
            // Insert Record structure
            StructureItem::create([
                "structure_id" => $structure->id,
                "item_id" => $item,
                "unit_cost" => 0,
            ]);
        }
        
        return redirect(route('structure.index'))->withSuccess('Record Successfully Created!');
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

    // Fetch records
    public function fetchItemsFromCmbis(Request $request){
        $search = $request->search;

        if($search == ''){
        $items = DB::connection('mysqlCmbis')
        ->table('item')
        ->where('QuantityOnHand', '!=', '0')
        ->select('ItemId as id', 'ItemCode', 'Description');
        }else{
        $items = DB::connection('mysqlCmbis')
        ->table('item')
        ->where('Description', 'like', '%' .$search . '%')
        ->where('QuantityOnHand', '!=', '0')
        ->select('ItemId as id', 'ItemCode', 'Description');
        }
        $data = $items->paginate(10, ['*'], 'page', $request->page);
        return response()->json($data); 
    } 
}
