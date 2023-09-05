@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Employees Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('employee.create') }}"> Create New Employee </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Email</th>
                  <th>Full Name</th>
                  <th>Position</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($data as $key => $employee)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $employee->user->email }}</td>
                   <td>{{ $employee->prefix }} {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }} {{ $employee->suffix }}</td>
                   <td>{{ $employee->position  }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('employee.edit',$employee->id) }}">Edit</a>
                       {!! Form::open(['method' => 'DELETE','route' => ['employee.destroy', $employee->id],'style'=>'display:inline']) !!}
                           {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                       {!! Form::close() !!}
                   </td>
                 </tr>
                @endforeach
               </table>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection