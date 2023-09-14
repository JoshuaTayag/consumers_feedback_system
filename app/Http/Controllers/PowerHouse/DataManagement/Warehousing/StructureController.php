<?php

namespace App\Http\Controllers\PowerHouse\DataManagement\Warehousing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataManagement\Structure;
use App\Models\DataManagement\StructureItem;
use App\Models\DataManagement\StockedItem;
use App\Models\Temporary\TempStructureItem;
use Illuminate\Support\Facades\Auth;
use DB;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $structures = Structure::with('structure_items')->orderBy('id','DESC')->paginate(10);
        return view('power_house.warehousing.data_management.structure_index',compact('structures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $temp_items = TempStructureItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
        // dd($temp_items);
        return view('power_house.warehousing.data_management.structure_create', compact('temp_items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
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

        $temp_items = TempStructureItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();

        foreach($temp_items as $item){ // $interests array contains input data
            // Insert Record structure
            StructureItem::create([
                "structure_id" => $structure->id,
                "item_id" => $item->item_id,
                "unit_cost" => $item->unit_cost,
                "quantity" => $item->quantity,
            ]);
        }

        TempStructureItem::with('item')->where('user_id', Auth::id())->delete();
        
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
        $temp_items = StructureItem::with('item')->where('structure_id', $id)->orderBy('id','DESC')->get();
        $structure = Structure::find($id);
        // dd($temp_items);
        return view('power_house.warehousing.data_management.structure_edit', compact('temp_items','structure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'structure_code' => ['required', 'string', 'max:255', 'unique:structures,structure_code,'.$id],
            'status' => ['required']
        ]);

        $structure = Structure::find($id);
        $structure->structure_code = $request->structure_code;
        $structure->status = $request->status;
        $structure->remarks = $request->remarks;
        $structure->save();

        return redirect(route('structure.index'))->withSuccess('Record Successfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $structure = Structure::find($id);
        $structure->structure_items()->delete();
        $structure->delete();

        return redirect(route('structure.index'))->withSuccess('Record Successfully Deleted!');
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

    public function updateStructureItem(Request $request)
    {
        $item_id = (int)$request->item_id;
        $structure_id = $request->structure_id;
        // dd($request->structure_id);
        if($request->ajax() && $request->type != "update"){

            $temp_item = DB::table('temp_structure_items')
            ->where('user_id', '=', Auth::id())
            ->where('item_id', '=', $item_id)
            ->first();

            $item = StockedItem::find($item_id);

            if($temp_item == null){
                DB::table('temp_structure_items')->insert(
                    array("user_id" => Auth::id(),
                            "item_id" => $item_id,
                            "quantity" => 1,
                            "unit_cost" => $item->AveragePrice,
                            )
                );
            }
            else{
                DB::table('temp_structure_items')
                ->where('id', $temp_item->id)
                ->update(
                    array("quantity" => $temp_item->quantity+1)
                );
            }
            
            $temp_items = TempStructureItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
            
            return view('power_house.warehousing.data_management.structure_get_temp_items')->with(compact('temp_items'))->render();
        }
        else{

            $temp_item = DB::table('structure_items')
            ->where('structure_id', $structure_id)
            ->where('item_id', '=', $item_id)
            ->first();
            // dd($item);
            $item = StockedItem::find($item_id);

            if($temp_item == null){
                DB::table('structure_items')->insert(
                    array("structure_id" => $structure_id,
                            "item_id" => $item_id,
                            "quantity" => 1,
                            "unit_cost" => $item->AveragePrice,
                            )
                );
            }
            else{
                DB::table('structure_items')
                ->where('id', $temp_item->id)
                ->update(
                    array("quantity" => $temp_item->quantity+1)
                );
            }
            $temp_items = StructureItem::with('item')->where('structure_id', $structure_id)->orderBy('id','DESC')->get();

            return view('power_house.warehousing.data_management.structure_get_temp_items')->with(compact('temp_items'))->render();

        }
    }

    public function structureUpdateQuantity(Request $request)
    {
        // dd($request);
        if ($request->ajax() && $request->name == "create") {
            DB::table('temp_structure_items')
                ->where('id', $request->pk)
                ->where('user_id', Auth::id())
                ->update([
                    "quantity" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
        else{
            DB::table('structure_items')
                ->where('id', $request->pk)
                ->update([
                    "quantity" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }   
    }

    public function structureUpdateCost(Request $request)
    {
        // dd($request->pk);
        if ($request->ajax() && $request->name == "create") {
            DB::table('temp_structure_items')
                ->where('id', $request->pk)
                ->where('user_id', Auth::id())
                ->update([
                    "unit_cost" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
        else{
            DB::table('structure_items')
                ->where('id', $request->pk)
                ->update([
                    "unit_cost" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }

    public function structureDeleteItem(Request $request)
    {
        // dd($request);
        $item_id = (int)$request->item_id;
        if($request->ajax() && $request->type == "create"){

            DB::table('temp_structure_items')->where('id', $item_id)->delete();
            
            $temp_items = TempStructureItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
            
            return view('power_house.warehousing.data_management.structure_get_temp_items')->with(compact('temp_items'))->render();
        }
        else{

            DB::table('structure_items')->where('id', $item_id)->delete();
            
            $temp_items = StructureItem::with('item')->where('structure_id', $request->structure_id)->orderBy('id','DESC')->get();
            
            return view('power_house.warehousing.data_management.structure_get_temp_items')->with(compact('temp_items'))->render();
        }
    }
}
