@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Create New User</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'userStore','method'=>'POST')) !!}
          <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                  <div class="form-group">
                      <strong>Name:</strong>
                      {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                  <div class="form-group">
                      <strong>Email:</strong>
                      {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                  <div class="form-group">
                      <strong>Password:</strong>
                      {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                  <div class="form-group">
                      <strong>Confirm Password:</strong>
                      {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                  <div class="form-group">
                      <strong>Role:</strong>
                      {!! Form::select('roles[]', $roles,[], array('class' => 'form-control')) !!}
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