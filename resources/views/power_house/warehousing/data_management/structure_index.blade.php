@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Structure Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('structure.create') }}"> Create New Structure </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>Code</th>
                  <th>Notes</th>
                  <th>Items</th>
                  <th>Status</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($structures as $key => $structure)
                 <tr>
                   <td>{{ $structure->structure_code }}</td>
                   <td>{{ $structure->remarks }}</td>
                   <td>{{ $structure->structure_items[0] }}</td>
                   <td>{{ $structure->status == 1 ? "Active" : "In Active"  }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('users.edit',$structure->id) }}">Edit</a>
                       {!! Form::open(['method' => 'DELETE','route' => ['userDestroy', $structure->id],'style'=>'display:inline']) !!}
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