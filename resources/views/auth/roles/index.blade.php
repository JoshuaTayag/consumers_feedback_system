@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Role Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    @can('role-create')
                      <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role </a>
                    @endcan
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th width="280px">Action</th>
                </tr>
                  @foreach ($roles as $key => $role)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $role->name }}</td>
                      <td>
                          <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
                          @can('role-edit')
                              <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                          @endcan
                          @can('role-delete')
                              {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                  {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                              {!! Form::close() !!}
                          @endcan
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