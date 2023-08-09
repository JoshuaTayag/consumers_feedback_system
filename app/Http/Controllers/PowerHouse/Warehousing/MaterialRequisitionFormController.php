<?php

namespace App\Http\Controllers\PowerHouse\Warehousing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialRequisitionForm;
use App\Models\DataManagement\Structure;
use App\Models\DataManagement\StructureItem;
use App\Models\Temporary\TempMaterialRequisitionFormItem;
use Illuminate\Support\Facades\Auth;
use DB;

class MaterialRequisitionFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mrfs = MaterialRequisitionForm::orderBy('id','DESC')->paginate(10);
        // dd($structures);
        return view('power_house.warehousing.material_requisition_form_index', compact('mrfs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $structures = Structure::orderBy('id','DESC')->get();
        $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();

        // dd($temp_items);

        return view('power_house.warehousing.material_requisition_form_create', compact('structures','temp_items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate requests
        $this->validate($request, [
            'project_name' => ['required', 'string', 'max:255']
        ]);

        $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
        foreach($temp_items as $item){ 
            dd($item->item->ItemCode);
            
            // Insert Record structure
            StructureItem::create([
                "structure_id" => $structure->id,
                "item_id" => $item,
                "unit_cost" => 0,
            ]);
        }

        

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

    public function getItems(Request $request)
    {
        $structure_id = (int)$request->structure_id;
        if($request->ajax()){
            
            $structure = Structure::with('structure_items')->find(9);
            $items = $structure->structure_items;
            // dd($items);
            return view('power_house.warehousing.material_requisition_form_get_items')->with(compact('items'))->render();
        }
    }

    public function updateItems(Request $request)
    {
        $structure_id = (int)$request->structure_id;
        if($request->ajax()){
            
            $structure = Structure::with('structure_items')->find($structure_id);
            
            foreach ($structure->structure_items as $key => $item) {

                $temp_item = DB::table('temp_material_requisition_form_items')
                ->where('user_id', '=', Auth::id())
                ->where('item_id', '=', $item->item_id)
                ->first();

                if($temp_item == null){
                    DB::table('temp_material_requisition_form_items')->insert(
                        array("user_id" => Auth::id(),
                                "nea_code" => "SAMPLE CODE 0001",
                                "material_requisition_form_id" => 1,
                                "item_id" => $item->item_id,
                                "quantity" => 1,
                                "unit_cost" => $item->unit_cost,
                                )
                    );
                }
                else{
                    DB::table('temp_material_requisition_form_items')
                    ->where('id', $temp_item->id)
                    ->update(
                        array("quantity" => $temp_item->quantity+1)
                    );
                }
                
            }
            
            $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
            
            return view('power_house.warehousing.material_requisition_form_get_items')->with(compact('temp_items'))->render();
        }
    }

    public function updateItem(Request $request)
    {
        $item_id = (int)$request->item_id;
        if($request->ajax()){

            $temp_item = DB::table('temp_material_requisition_form_items')
            ->where('user_id', '=', Auth::id())
            ->where('item_id', '=', $item_id)
            ->first();

            if($temp_item == null){
                DB::table('temp_material_requisition_form_items')->insert(
                    array("user_id" => Auth::id(),
                            "nea_code" => "SAMPLE CODE 0001",
                            "material_requisition_form_id" => 1,
                            "item_id" => $item_id,
                            "quantity" => 1,
                            "unit_cost" => 0,
                            )
                );
            }
            else{
                DB::table('temp_material_requisition_form_items')
                ->where('id', $temp_item->id)
                ->update(
                    array("quantity" => $temp_item->quantity+1)
                );
            }
            
            $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
            
            return view('power_house.warehousing.material_requisition_form_get_items')->with(compact('temp_items'))->render();
        }
    }
    public function deleteItem(Request $request)
    {
        // dd($request->item_id);
        $item_id = (int)$request->item_id;
        if($request->ajax()){

            DB::table('temp_material_requisition_form_items')->where('id', $item_id)->delete();
            
            $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
            
            return view('power_house.warehousing.material_requisition_form_get_items')->with(compact('temp_items'))->render();
        }
    }

    public function updateItemQuantity(Request $request)
    {
        // dd($request->pk);
        if ($request->ajax()) {
            DB::table('temp_material_requisition_form_items')
                ->where('id', $request->pk)
                ->update([
                    "quantity" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }

    public function updateItemCode(Request $request)
    {
        // dd($request->pk);
        if ($request->ajax()) {
            DB::table('temp_material_requisition_form_items')
                ->where('id', $request->pk)
                ->update([
                    "nea_code" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }

    public function updateItemCost(Request $request)
    {
        // dd($request->pk);
        if ($request->ajax()) {
            DB::table('temp_material_requisition_form_items')
                ->where('id', $request->pk)
                ->update([
                    "unit_cost" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }
}
