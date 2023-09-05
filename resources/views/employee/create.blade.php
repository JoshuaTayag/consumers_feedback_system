@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Record New Employee</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('employee.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'employee.store','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}
          <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-1 mb-2">
                  <div class="form-group">
                      <strong>Prefix</strong>
                      {!! Form::text('prefix', null, array('placeholder' => 'e.g. Dr.','class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-3 mb-2">
                  <div class="form-group">
                      <strong>First Name</strong>
                      {!! Form::text('first_name', null, array('placeholder' => 'First Name','class' => 'form-control', 'required')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-3 mb-2">
                  <div class="form-group">
                      <strong>Middle Name</strong>
                      {!! Form::text('middle_name', null, array('placeholder' => 'Middle Name','class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-3 mb-2">
                  <div class="form-group">
                      <strong>Last Name</strong>
                      {!! Form::text('last_name', null, array('placeholder' => 'Last Name','class' => 'form-control', 'required')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-2 mb-2">
                  <div class="form-group">
                      <strong>Suffix</strong>
                      {!! Form::text('suffix', null, array('placeholder' => 'e.g. MBA','class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                  <div class="form-group">
                      <strong>User Email</strong>
                      {!! Form::select('user_id', $users,[], array('placeholder' => '-- Choose --', 'class' => 'form-control', 'required')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                  <div class="form-group">
                      <strong>Position</strong>
                      {!! Form::text('position', null, array('placeholder' => 'e.g. General Manager', 'class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                <div class="form-group">
                    <strong>Signature</strong>
                    {!! Form::file('signature_path', null, array( 'class' => 'form-control', 'required')) !!}
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 text-end">
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