@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Users Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Roles</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($data as $key => $user)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $user->name }}</td>
                   <td>{{ $user->email }}</td>
                   <td>
                     @if(!empty($user->getRoleNames()))
                       @foreach($user->getRoleNames() as $v)
                          <label class="badge badge-success">{{ $v }}</label>
                       @endforeach
                     @endif
                   </td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                       {!! Form::open(['method' => 'DELETE','route' => ['userDestroy', $user->id],'style'=>'display:inline']) !!}
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