<?php

namespace App\Http\Controllers\PowerHouse\Warehousing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialRequisitionForm;
use App\Models\DataManagement\StockedItem;
use App\Models\MaterialRequisitionFormItems;
use App\Models\User;
use App\Models\DataManagement\Structure;
use App\Models\DataManagement\StructureItem;
use App\Models\Temporary\TempMaterialRequisitionFormItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use DB;
use Carbon\Carbon;
use PDF;
use Image;

class MaterialRequisitionFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mrfs = MaterialRequisitionForm::with('items','district', 'municipality', 'barangay')->orderBy('id','DESC')->paginate(10);
        $unliquidated_mrf = MaterialRequisitionForm::where([['requested_id', Auth::id()], ['status', '<=', 2]])->count();
        $liquidations = DB::table('material_requisition_form_liquidations')->get();
        // dd($unliquidated_mrf);
        // dd($liquidations->where('material_requisition_form_id', 18));
        return view('power_house.warehousing.material_requisition_form_index', compact('mrfs','unliquidated_mrf','liquidations'));
    }

    public function mrfApprovalIndex()
    {
        $mrfs = MaterialRequisitionForm::with('items','district', 'municipality', 'barangay')
        ->where('status', 0)
        ->where('approved_id', Auth::id())
        ->orderBy('id','DESC')
        ->paginate(10);
        return view('power_house.warehousing.material_requisition_form_approvals', compact('mrfs'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unliquidated_mrf = MaterialRequisitionForm::where([['requested_id', Auth::id()], ['status', '<=', 2]])->count();
        $structures = Structure::orderBy('id','DESC')->get();
        $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
        $users = User::orderBy('name','asc')->get();
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();

        // dd($temp_items);

        if($unliquidated_mrf >= 5){
            // dd($unliquidated_mrf);
            return redirect(route('material-requisition-form.index'))->withWarning('Please Liquidate atleast one MRF to proceed!');
            return view('power_house.warehousing.material_requisition_form_create', compact('structures','temp_items','users','districts'))->withSuccess('Please Liquidate atleast one MRF to proceed!');
        }
        else{
            return view('power_house.warehousing.material_requisition_form_create', compact('structures','temp_items','users','districts'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate requests
        $this->validate($request, [
            'project_name' => ['required', 'string', 'max:255'],
            'approved_by' => ['required'],
            'requested_by' => ['required'],
            'area_id' => ['required'],
        ]);

        $unliquidated_mrf = MaterialRequisitionForm::where([['requested_id', $request->requested_by], ['status', '<=', 2]])->count();
        // check if this user has unliquidated MRF's
        if($unliquidated_mrf >= 5){
            return redirect(route('material-requisition-form.index'))->withWarning('Please Liquidate atleast one MRF to proceed!');
        }
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
            "requested_id" => $request->requested_by,
            "requested_by" => now(),
            "approved_id" => $request->approved_by,
            "area_id" => $request->area_id,
            // "approved_by" => now(),
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
        $mrf = MaterialRequisitionForm::with('items')->find($id);
        $structures = Structure::orderBy('id','DESC')->get();
        $users = User::orderBy('name','asc')->get();
        $liquidation = DB::table('material_requisition_form_liquidations')->where('material_requisition_form_id', $id)->get();
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        // dd($liquidation->first() ? "true" : 'false');
        return view('power_house.warehousing.material_requisition_form_edit', compact('structures','users','districts','mrf','liquidation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate requests
        $this->validate($request, [
            'project_name' => ['required', 'string', 'max:255'],
            'approved_by' => ['required'],
            'requested_by' => ['required'],
        ]);

        $material_requisition_form = MaterialRequisitionForm::find($id);
        $material_requisition_form->project_name = $request->project_name;
        $material_requisition_form->district_id = $request->district;
        $material_requisition_form->municipality_id = $request->municipality;
        $material_requisition_form->barangay_id = $request->barangay;
        $material_requisition_form->sitio = $request->sitio;
        $material_requisition_form->remarks = $request->remarks;
        // $material_requisition_form->status = $request->control_no;
        $material_requisition_form->requested_id = $request->requested_by;
        $material_requisition_form->approved_id = $request->approved_by;
        $material_requisition_form->area_id = $request->area_id;
        $material_requisition_form->save();
        
        return redirect(route('material-requisition-form.index'))->withSuccess('Record Successfully Created!');
    }

    public function mrfApprovalUpdate(Request $request, string $id)
    {
        if(!$request->exists('disapproved')){
            $material_requisition_form = MaterialRequisitionForm::find($id);
            $material_requisition_form->status = 1;
            $material_requisition_form->approved_by = Carbon::now();
            $material_requisition_form->save();

            return redirect(route('mrfApprovalIndex'))->withSuccess('Approved Successfully!');
        }
        else{
            $material_requisition_form = MaterialRequisitionForm::find($id);
            $material_requisition_form->status = 4;
            $material_requisition_form->approved_by = Carbon::now();
            $material_requisition_form->save();

            return redirect(route('mrfApprovalIndex'))->withSuccess('Successfully DisApproved!');
        }
        
    }

    public function mrfLiquidationCreate(Request $request, string $id)
    {
        if($request->has('assigning')){
            foreach ($request->mrvs as $key => $mrv) {
                DB::table('material_requisition_form_liquidations')->insert(
                    array("user_id" => Auth::id(),
                            "material_requisition_form_id" => $id,
                            "type" => "MRV",
                            "type_number" => $mrv,
                            "date_acted" => Carbon::now(),
                            "date_finished" => Carbon::now(),
                            "image_path" => null,
                            "remarks" => null,
                            "created_at" => Carbon::now(),
                            )
                );
            }
    
            foreach ($request->serivs as $key => $seriv) {
                DB::table('material_requisition_form_liquidations')->insert(
                    array("user_id" => Auth::id(),
                            "material_requisition_form_id" => $id,
                            "type" => "SERIV",
                            "type_number" => $seriv,
                            "date_acted" => Carbon::now(),
                            "date_finished" => Carbon::now(),
                            "image_path" => null,
                            "remarks" => null,
                            "created_at" => Carbon::now(),
                            )
                );
            }

            $material_requisition_form = MaterialRequisitionForm::find($id);

            if($request->wo_no){
                // DB::table('material_requisition_form_liquidations')->insert(
                //     array("user_id" => Auth::id(),
                //             "material_requisition_form_id" => $id,
                //             "type" => "WO",
                //             "type_number" => $request->wo_no,
                //             "date_acted" => Carbon::now(),
                //             "date_finished" => Carbon::now(),
                //             "image_path" => null,
                //             "remarks" => null,
                //             "created_at" => Carbon::now(),
                //             )
                // );
                $material_requisition_form->with_wo = $request->wo_no;
                $material_requisition_form->status = 2;
                
            }
            
            $material_requisition_form->save();

            return redirect(route('material-requisition-form.index'))->withSuccess('MRV/SERIV Successfully Assign!');
        }

        else{
            $this->validate($request, [
                // 'type_number' => [ 
                //     Rule::unique('material_requisition_form_liquidations')->where(function ($query) use($request) {
                //         return $query->where('type', 'MRV');
                //         // ->where('type_number', $request->mrvs);
                //     }),
                // ],
                // 'type_number' => [ 
                //     Rule::unique('material_requisition_form_liquidations')->where(function ($query) use($request) {
                //         return $query->where('type', 'WO')
                //         ->where('type_number', $request->wo_no); 
                //     }),
                // ],
                // 'wo_no' => ['unique:material_requisition_form_liquidations,type_number'],
                // 'approved_by' => ['required'],
                // 'requested_by' => ['required'],
            ]);
            
            
            $input = $request->all();
            $image_path = $request->file('image_path');
            if($image_path){
                // dd('test');
                $resize = Image::make($image_path)
                ->resize(600, null, function ($constraint) { $constraint->aspectRatio(); } )
                ->encode('jpg',80);

                // calculate md5 hash of encoded image
                $hash = md5($resize->__toString());

                // use hash as a name
                $path = "images/mrf_images/{$hash}.jpg";

                // save it locally to ~/public/images/{$hash}.jpg
                $resize->save(public_path($path));

                $input['image_path'] = $path;
            }
            

            // dd($path);
            // foreach ($request->mrvs as $key => $mrv) {
            //     DB::table('material_requisition_form_liquidations')->insert(
            //         array("user_id" => Auth::id(),
            //                 "material_requisition_form_id" => $id,
            //                 "type" => "MRV",
            //                 "type_number" => $mrv,
            //                 "date_acted" => $request->date_acted,
            //                 "date_finished" => $request->date_finished,
            //                 "image_path" => $request->image_path,
            //                 "remarks" => $request->remarks,
            //                 "lineman" => $request->lineman,
            //                 "created_at" => Carbon::now(),
            //                 )
            //     );
            // }

            // foreach ($request->serivs as $key => $seriv) {
            //     DB::table('material_requisition_form_liquidations')->insert(
            //         array("user_id" => Auth::id(),
            //                 "material_requisition_form_id" => $id,
            //                 "type" => "SERIV",
            //                 "type_number" => $seriv,
            //                 "date_acted" => $request->date_acted,
            //                 "date_finished" => $request->date_finished,
            //                 "image_path" => $request->image_path,
            //                 "remarks" => $request->remarks,
            //                 "lineman" => $request->lineman,
            //                 "created_at" => Carbon::now(),
            //                 )
            //     );
            // }

            // if($request->wo_no){
            //     DB::table('material_requisition_form_liquidations')->insert(
            //         array("user_id" => Auth::id(),
            //                 "material_requisition_form_id" => $id,
            //                 "type" => "WO",
            //                 "type_number" => $request->wo_no,
            //                 "date_acted" => $request->date_acted,
            //                 "date_finished" => $request->date_finished,
            //                 "image_path" => $request->image_path,
            //                 "remarks" => $request->remarks,
            //                 "created_at" => Carbon::now(),
            //                 )
            //     );
            // }

            if($request->mcrt_no){
                DB::table('material_requisition_form_liquidations')->insert(
                    array("user_id" => Auth::id(),
                            "material_requisition_form_id" => $id,
                            "type" => "MCRT",
                            "type_number" => $request->mcrt_no,
                            "date_acted" => $request->date_acted,
                            "date_finished" => $request->date_finished,
                            "image_path" => $request->image_path,
                            "remarks" => $request->remarks,
                            "lineman" => $request->lineman,
                            "created_at" => Carbon::now(),
                            )
                );
            }

            if($request->mst_no){
                DB::table('material_requisition_form_liquidations')->insert(
                    array("user_id" => Auth::id(),
                            "material_requisition_form_id" => $id,
                            "type" => "MST",
                            "type_number" => $request->mst_no,
                            "date_acted" => $request->date_acted,
                            "date_finished" => $request->date_finished,
                            "image_path" => $request->image_path,
                            "remarks" => $request->remarks,
                            "lineman" => $request->lineman,
                            "created_at" => Carbon::now(),
                            )
                );
            }



            // dd($request->quantity[0]);
            foreach ($request->item_ids as $key => $item) {
                $material_requisition_form_item = MaterialRequisitionFormItems::find($item);
                $material_requisition_form_item->liquidation_quantity = $request->quantity[$key];
                $material_requisition_form_item->save();
            }

            $material_requisition_form = MaterialRequisitionForm::find($id);
            $material_requisition_form->status = 3;
            $material_requisition_form->liquidated_id = Auth::id();
            $material_requisition_form->liquidated_by = Carbon::now();
            // $material_requisition_form->with_wo = $request->filled('wo_no');
            $material_requisition_form->save();
            
            return redirect(route('material-requisition-form.index'))->withSuccess('Record Successfully Liquidated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mrf = MaterialRequisitionForm::find($id);
        $mrf_items = MaterialRequisitionFormItems::where('material_requisition_form_id', $id);

        $mrf->delete();
        $mrf_items->delete();

        return redirect(route('material-requisition-form.index'))->withSuccess('Record Successfully deleted!');
    }

    public function getItems(Request $request)
    {
        $structure_id = (int)$request->structure_id;
        if($request->ajax()){
            
            $structure = Structure::with('structure_items')->find(9);
            $items = $structure->structure_items;
            // dd($items);
            return view('power_house.warehousing.material_requisition_form_get_temp_items')->with(compact('items'))->render();
        }
    }

    public function mrfUpdateItems(Request $request)
    {
        $structure_id = (int)$request->structure_id;
        $mrf_id = (int)$request->mrf_id;
        // dd($mrf_id);
        if($request->ajax()){
            
            $structure = Structure::with('structure_items')->find($structure_id);
            
            foreach ($structure->structure_items as $key => $item) {

                $mrf_item = DB::table('material_requisition_form_items')
                ->where('material_requisition_form_id', '=', $mrf_id)
                ->where('item_id', '=', $item->item_id)
                ->first();
                // dd($item->item);
                if($mrf_item == null){
                    DB::table('material_requisition_form_items')->insert(
                        array("nea_code" => $item->item->ItemCode,
                                "material_requisition_form_id" => $mrf_id,
                                "item_id" => $item->item_id,
                                "quantity" => $item->quantity,
                                "unit_cost" => $item->unit_cost,
                                )
                    );
                }
                else{
                    DB::table('material_requisition_form_items')
                    ->where('id', $mrf_item->id)
                    ->update(
                        array("quantity" => $mrf_item->quantity+$item->quantity)
                    );
                }
                
            }
            
            $mrf = MaterialRequisitionForm::with('items')->find($mrf_id);
            // dd($mrf);
            return view('power_house.warehousing.material_requisition_form_get_items')->with(compact('mrf'))->render();
        }
    }

    public function mrfUpdateItem(Request $request)
    {
        $item_id = (int)$request->item_id;
        $mrf_id = (int)$request->mrf_id;

        if($request->ajax()){

            $mrf_item = DB::table('material_requisition_form_items')
            ->where('material_requisition_form_id', '=', $mrf_id)
            ->where('item_id', '=', $item_id)
            ->first();

            $item = StockedItem::find($item_id);

            if($mrf_item == null){
                DB::table('material_requisition_form_items')->insert(
                    array("nea_code" => $item->ItemCode,
                            "material_requisition_form_id" => $mrf_id,
                            "item_id" => $item_id,
                            "quantity" => 1,
                            "unit_cost" => $item->AveragePrice,
                            )
                );
            }
            else{
                DB::table('material_requisition_form_items')
                ->where('id', $mrf_item->id)
                ->update(
                    array("quantity" => $mrf_item->quantity+1)
                );
            }

            $mrf = MaterialRequisitionForm::with('items')->find($mrf_id);
            // dd($mrf);
            return view('power_house.warehousing.material_requisition_form_get_items')->with(compact('mrf'))->render();
        }
    }

    public function mrfDeleteItem(Request $request)
    {
        // dd($request->item_id);
        $item_id = (int)$request->item_id;
        $mrf_id = (int)$request->mrf_id;
        if($request->ajax()){

            DB::table('material_requisition_form_items')->where('id', $item_id)->delete();
            
            $mrf = MaterialRequisitionForm::with('items')->find($mrf_id);
            
            return view('power_house.warehousing.material_requisition_form_get_items')->with(compact('mrf'))->render();
        }
    }

    public function mrfUpdateItemQuantity(Request $request)
    {
        // dd($request->pk);
        if ($request->ajax()) {
            DB::table('material_requisition_form_items')
                ->where('id', $request->pk)
                ->update([
                    "quantity" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }

    public function mrfUpdateItemCode(Request $request)
    {
        // dd($request->pk);
        if ($request->ajax()) {
            DB::table('material_requisition_form_items')
                ->where('id', $request->pk)
                ->update([
                    "nea_code" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }

    public function mrfUpdateItemCost(Request $request)
    {
        // dd($request->pk);
        if ($request->ajax()) {
            DB::table('material_requisition_form_items')
                ->where('id', $request->pk)
                ->update([
                    "unit_cost" => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }


    //Temp ITEMS
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

                $stocked_item = StockedItem::find($item->item_id);

                if($temp_item == null){
                    DB::table('temp_material_requisition_form_items')->insert(
                        array("user_id" => Auth::id(),
                                "nea_code" => $stocked_item->ItemCode,
                                "material_requisition_form_id" => 1,
                                "item_id" => $item->item_id,
                                "quantity" => $item->quantity,
                                "unit_cost" => $item->unit_cost,
                                )
                    );
                }
                else{
                    DB::table('temp_material_requisition_form_items')
                    ->where('id', $temp_item->id)
                    ->update(
                        array("quantity" => $temp_item->quantity+$item->quantity)
                    );
                }
                
            }
            
            $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
            
            return view('power_house.warehousing.material_requisition_form_get_temp_items')->with(compact('temp_items'))->render();
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

            $item = StockedItem::find($item_id);

            if($temp_item == null){
                DB::table('temp_material_requisition_form_items')->insert(
                    array("user_id" => Auth::id(),
                            "nea_code" => $item->ItemCode,
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
            
            return view('power_house.warehousing.material_requisition_form_get_temp_items')->with(compact('temp_items'))->render();
        }
    }
    public function deleteItem(Request $request)
    {
        // dd($request->item_id);
        $item_id = (int)$request->item_id;
        if($request->ajax()){

            DB::table('temp_material_requisition_form_items')->where('id', $item_id)->delete();
            
            $temp_items = TempMaterialRequisitionFormItem::with('item')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
            
            return view('power_house.warehousing.material_requisition_form_get_temp_items')->with(compact('temp_items'))->render();
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
    
    public function mrfPrintPdf(Request $request, string $id)
    {
        $mrf = MaterialRequisitionForm::with('items')->find($id);
        $mrf_liquidation = DB::table('material_requisition_form_liquidations')->where('material_requisition_form_id', $id)->get();
        
        
        // dd($mrf_liquidation);
        view()->share('datas', [$mrf, $mrf_liquidation]);
        $pdf = PDF::loadView('power_house.warehousing.material_requisition_form_pdf');
        return $pdf->stream();
    }

    public function getMrvs(Request $request){
        $search = $request->search;
        $liquidated_mrvs = DB::table('material_requisition_form_liquidations')->where('type', 'MRV')->pluck('type_number');
        if($search == ''){
        $accounts = DB::connection('mysqlCmbis')
        ->table('materialrequisition')
        ->orderBy('MaterialRequisitionId','DESC')
        ->whereNotIn('RVNumber', $liquidated_mrvs)
        ->select('RVNumber as id');
        }else{
        $accounts = DB::connection('mysqlCmbis')
        ->table('materialrequisition')
        ->where('RVNumber', 'like', '%' .$search . '%')
        ->orderBy('MaterialRequisitionId','DESC')
        ->whereNotIn('RVNumber', $liquidated_mrvs)
        ->select('RVNumber as id');
        }
        $data = $accounts->paginate(10, ['*'], 'page', $request->page);
        return response()->json($data); 
    } 

    public function getSerivs(Request $request){
        $search = $request->search;
        $liquidated_seriv = DB::table('material_requisition_form_liquidations')->where('type', 'SERIV')->pluck('type_number');
        if($search == ''){
        $accounts = DB::connection('mysqlCmbis')
        ->table('seriv')
        ->orderBy('SerivId','DESC')
        ->whereNotIn('SERIVNum', $liquidated_seriv)
        ->select('SERIVNum as id');
        }else{
        $accounts = DB::connection('mysqlCmbis')
        ->table('seriv')
        ->where('RVNumber', 'like', '%' .$search . '%')
        ->orderBy('SerivId','DESC')
        ->whereNotIn('SERIVNum', $liquidated_seriv)
        ->select('SERIVNum as id');
        }
        $data = $accounts->paginate(10, ['*'], 'page', $request->page);
        return response()->json($data); 
    } 

    public function mrfLiquidate(Request $request, string $id)
    {
        $mrf = MaterialRequisitionForm::with('items')->find($id);
        $structures = Structure::orderBy('id','DESC')->get();
        $users = User::orderBy('name','asc')->get();
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        return view('power_house.warehousing.material_requisition_form_liquidation', compact('mrf','users','districts', 'structures'));
    }

    public function viewLiquidatedMrf(string $id){
        $mrf = MaterialRequisitionForm::with('items')->find($id);
        $mrf_liquidation = DB::table('material_requisition_form_liquidations')->where('material_requisition_form_id', $mrf->id)->get();
        // dd($mrf_liquidation);
        return view('power_house.warehousing.material_requisition_form_liquidated_view', compact('mrf', 'mrf_liquidation'));
    }

    public function mrfLiquidationReport()
    {
        // $mrf = MaterialRequisitionForm::with('items')->find(16);
        $mrfs = [];
        $mrf = MaterialRequisitionForm::all();
        // $mrf = DB::table('material_requisition_forms', 'material_requisition_form_liquidations')->select('material_requisition_forms.project_name, material_requisition_form_liquidations.type, material_requisition_form_liquidations.type_number')
        // ->from('material_requisition_forms')
        // ->join('material_requisition_form_liquidations')
        // ->on('material_requisition_forms.id = material_requisition_form_liquidations.material_requisition_form_id');

        // $mrf = DB::table('material_requisition_forms')
        // ->select('material_requisition_forms.project_name', 'material_requisition_forms.status' , 'material_requisition_form_liquidations.type' , 'material_requisition_form_liquidations.type_number')
        // ->join('material_requisition_form_liquidations', 'material_requisition_forms.id', '=', 'material_requisition_form_liquidations.material_requisition_form_id')
        // ->where('material_requisition_forms.status', 2)
        // ->get();

        

        $mrfs['pending'] = $mrf->where('status', 0)->count();
        $mrfs['approved'] = $mrf->where('status', 1)->count();
        $mrfs['liquidated'] = $mrf->where('status', 2)->count();
        $mrfs['disapproved'] = $mrf->where('status', 3)->count();

        $mrfs['pending_with_wo'] = $mrf->where('status', 0)->where('with_wo')->count();
        $mrfs['approved_with_wo'] = $mrf->where('status', 1)->where('with_wo')->count();
        $mrfs['liquidated_with_wo'] = $mrf->where('status', 2)->where('with_wo')->count();
        $mrfs['disapproved_with_wo'] = $mrf->where('status', 3)->where('with_wo')->count();
        
        dd($mrfs);
        view()->share('datas', $mrf);
        $pdf = PDF::loadView('power_house.warehousing.mrf_liquidation_report_view');
        return $pdf->stream();
    }
}
