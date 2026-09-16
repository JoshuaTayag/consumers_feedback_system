@extends('layouts.app')


@section('content')
@php
  $permissionGroups = $permission
    ->groupBy(fn ($value) => Str::beforeLast($value->name, '-'))
    ->sortKeys();
@endphp

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Edit Role</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::model($role, ['method' => 'PUT','route' => ['roles.update', $role->id]]) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                    <div class="form-group">
                        <strong>Permission:</strong>
                        <br/>
                        <div class="row">
                          <div class="col-lg-3">
                            <input type="checkbox" id="checkAll" > Check All
                          </div>
                          <div class="col-lg-12 py-0 my-0">
                            <hr>
                          </div>
                        </div>
                        <div class="row g-3">
                          @foreach($permissionGroups as $groupName => $groupPermissions)
                            <div class="col-lg-12">
                              <div class="border rounded p-3">
                                <h6 class="fw-bold text-capitalize mb-3">
                                  <i class="fas fa-folder-open me-2"></i>{{ str_replace('-', ' ', $groupName) }}
                                </h6>
                                <div class="row">
                                  @foreach($groupPermissions->sortBy('name') as $value)
                                    <div class="col-lg-3 mb-2">
                                      <label class="d-flex align-items-center gap-2">
                                        {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                        <span>{{ $value->name }}</span>
                                      </label>
                                    </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
$('#checkAll').click(function () {    
  $('input:checkbox').prop('checked', this.checked);    
});
</script>
@endsection