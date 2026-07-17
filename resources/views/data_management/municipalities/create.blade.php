@extends('layouts.app')


@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <span class="mb-0 align-middle fs-3">Record New Municipality</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('municipalities.index') }}"> Back </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open(['route' => 'municipalities.store', 'method' => 'POST']) !!}
            <div class="row g-2 align-items-end">
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <strong>District</strong>
                  {!! Form::select(
                      'district_id',
                      $districts->pluck('district_name', 'id'),
                      [],
                      ['placeholder' => '-- Choose --', 'class' => 'form-control', 'required'],
                  ) !!}
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <strong>Municipality</strong>
                  {!! Form::text(
                      'municipality_name',
                      null,
                      ['placeholder' => 'Enter Municipality Name', 'class' => 'form-control', 'required']
                  ) !!}
                </div>
              </div>
              <div class="col-12 col-md-12 text-md-end">
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
