@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Generate Change Meter Report</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    @can('service-connect-order-create')
                      <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}"> Back </a>
                    @endcan
                  </div>
              </div>
            </div>
            <form action="{{ route('generateReport') }}" method="GET">
              <div class="row p-3">
                <div class="col-lg-3">
                  {{ Form::label('date_from', 'Date From;') }}
                  {{ Form::date('date_from', null, array('class' => 'form-control', 'required')) }}
                </div>
                <div class="col-lg-3">
                  {{ Form::label('date_to', 'Date To;') }}
                  {{ Form::date('date_to', null, array('class' => 'form-control', 'required')) }}
                </div>
                <div class="col-lg-3">
                  <label for="app_status" class="form-label mb-1">Application Status *</label>
                  <select id="app_status" class="form-control" name="app_status" required>
                    <option value="0" id="">All</option>
                    <option value="1" id="">INSTALLED</option>
                    <option value="2" id="">UNACTED</option>
                    <option value="3" id="">ACTED</option>
                    <option value="4" id="">NOT COMPLETED</option>
                  </select>
                </div>
                <div class="col-lg-3 d-flex align-items-end gap-2">
                  <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                  <button type="button" class="btn btn-info" onclick="clearSearch()">Clear</button>
                </div>
              </div>
            </form>
            <div class="card-body">
              <div class="row">
             
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection