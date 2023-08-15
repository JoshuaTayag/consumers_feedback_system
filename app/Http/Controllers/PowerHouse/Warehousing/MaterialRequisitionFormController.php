<?php

namespace App\Http\Controllers\PowerHouse\Warehousing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialRequisitionForm;
use App\Models\User;
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
        $mrfs = MaterialRequisitionForm::with('items','district', 'municipality', 'barangay')->orderBy('id','DESC')->paginate(10);
        // dd($mrfs);
        return view('power_house.warehousing.material_requisition_form_index', compact('mrfs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $structures = Structure::orderBy('id','DESC')->get();
        $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
        $users = User::orderBy('name','asc')->get();
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();

        // dd($temp_items);

        return view('power_house.warehousing.material_requisition_form_create', compact('structures','temp_items','users','districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate requests
        $this->validate($request, [
            'project_name' => ['required', 'string', 'max:255'],
            'approved_by' => ['required']
            
        ]);
        // dd($request);
        $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
        $material_requisition_form = MaterialRequisitionForm::create([
            "project_name" => $request->project_name,
            "district_id" => $request->district,
            "municipality_id" => $request->municipality,
            "barangay_id" => $request->barangay,
            "sitio" => $request->sitio,
            "remarks" => $request->remarks,
            "status" => 0,
            "requested_id" => Auth::id(),
            "requested_by" => now(),
        ]);
        foreach($temp_items as $item){ 
            // Insert Record structure
            DB::table('material_requisition_form_items')->insert([
                "nea_code" => $item->nea_code ? $item->nea_code : $item->item->ItemCode,
                "material_requisition_form_id" => $material_requisition_form->id,
                "item_id" => $item->item_id,
                "quantity" => $item->quantity,
                "unit_cost" => $item->unit_cost,
            ]);
        }

        // Insert Record structure
        TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->delete();
        
        return redirect(route('material-requisition-form.index'))->withSuccess('Record Successfully Created!');
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
