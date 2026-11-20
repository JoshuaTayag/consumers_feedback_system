<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataManagement\Municipality;
use App\Models\DataManagement\Barangay;

class AddressController extends Controller
{
    public function fetchMunicipalities(Request $request)
    {
        $query = Municipality::query();

        if ($request->boolean('all')) {
            $query->orderBy('municipality_name', 'asc');
        } else {
            $query->where("district_id", $request->district_id);
        }

        $data['municipalities'] = $query->get(["municipality_name", "id"]);
  
        return response()->json($data);
    }

    public function fetchBarangays(Request $request)
    {
        $data['barangays'] = Barangay::where("municipality_id", $request->municipality_id)->get(["barangay_name", "id"]);
  
        return response()->json($data);
    }
}
