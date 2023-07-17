<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;
use App\Models\Barangay;

class AddressController extends Controller
{
    public function fetchMunicipalities(Request $request)
    {
        $data['municipalities'] = Municipality::where("district_id", $request->district_id)->get(["municipality_name", "id"]);
  
        return response()->json($data);
    }

    public function fetchBarangays(Request $request)
    {
        $data['barangays'] = Barangay::where("municipality_id", $request->municipality_id)->get(["barangay_name", "id"]);
  
        return response()->json($data);
    }
}
