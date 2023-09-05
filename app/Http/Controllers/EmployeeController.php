<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Image;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Employee::with('user')->orderBy('id','DESC')->paginate(5);
        return view('employee.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::pluck('user_id')->all();
        $users = User::whereNotIn('id', $employees)->pluck('email','id')->all();
        return view('employee.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'user_id' => 'required|unique:employees,user_id',
        ]);
    
        $input = $request->all();
        $signature = $request->file('signature_path');
        if($signature){
            // dd('test');
            $resize = Image::make($signature)
            ->resize(600, null, function ($constraint) { $constraint->aspectRatio(); } )
            ->encode('jpg',80);

            // calculate md5 hash of encoded image
            $hash = md5($resize->__toString());

            // use hash as a name
            $path = "images/signatures/{$hash}.jpg";

            // save it locally to ~/public/images/{$hash}.jpg
            $resize->save(public_path($path));
        }
        $input['signature_path'] = $path;
        $user = Employee::create($input);
    
        return redirect()->route('employee.index')
                        ->with('success','Record successfully created');
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
        $employees = Employee::where('id', '!=', $id)->pluck('user_id')->all();
        $users = User::whereNotIn('id', $employees)->pluck('email', 'id')->all();
        $employee = Employee::find($id);
        return view('employee.edit',compact('users', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'user_id' => 'required|unique:employees,user_id,'.$id,
        ]);
    
        $input = $request->all();
        
        $signature = $request->file('signature_path');
        if($signature){
            // dd('test');
            $resize = Image::make($signature)
            ->resize(600, null, function ($constraint) { $constraint->aspectRatio(); } )
            ->encode('jpg',80);

            // calculate md5 hash of encoded image
            $hash = md5($resize->__toString());

            // use hash as a name
            $path = "images/signatures/{$hash}.jpg";

            // save it locally to ~/public/images/{$hash}.jpg
            $resize->save(public_path($path));
            $input['signature_path'] = $path;
        }
        

        $employee = Employee::find($id);
        $employee->update($input);
    
        return redirect()->route('employee.index')
                        ->with('success','Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        $employee->delete();

        return redirect(route('employee.index'))->withSuccess('Record Successfully deleted!');
    }
}
