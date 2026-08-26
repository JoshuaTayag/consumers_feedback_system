@extends('layouts.app')


@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <span class="mb-0 align-middle fs-3">Edit Kwh Meter Damage Cause Type</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('kwh-meter-damage-cause-types.index') }}"> Back </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open(['route' => ['kwh-meter-damage-cause-types.update', $kwhMeterDamageCauseType->id], 'method' => 'PUT']) !!}
            <div class="row g-2 align-items-end">
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <strong>Name</strong>
                  {!! Form::text(
                      'name',
                      $kwhMeterDamageCauseType->name,
                      ['placeholder' => 'Enter Name', 'class' => 'form-control', 'required']
                  ) !!}
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <strong>Description</strong>
                  {!! Form::text(
                      'description',
                      $kwhMeterDamageCauseType->description,
                      ['placeholder' => 'Enter Description', 'class' => 'form-control', 'required']
                  ) !!}
                </div>
              </div>
              <div class="col-12 col-md-12 text-md-start">
                <div class="form-group mb-0">
                  <strong class="d-block">&nbsp;</strong>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
