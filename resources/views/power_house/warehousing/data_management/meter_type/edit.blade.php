@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Edit Meter Type</span>
              </div>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('meter-type.update', $meter_type->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="meter_brand" class="form-label mb-1">Meter Brand</label>
                    <input type="text" class="form-control" id="meter_brand" name="meter_brand" value="{{ $meter_type->meter_brand }}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="meter_code" class="form-label mb-1">Meter Code</label>
                    <input type="text" class="form-control" id="meter_code" name="meter_code" old="meter_code" value="{{ $meter_type->meter_code }}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="meter_type" class="form-label mb-1">Meter Type</label>
                    <select class="form-select" id="meter_type" name="meter_type" required>
                      <option value="">Select Meter Type</option>
                      @foreach(config('constants.meter_control_type') as $controlType)
                          <option value="{{ $controlType['name'] }}" @selected($controlType['name'] == $meter_type->meter_type)>{{ $controlType['name'] }}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-2">
                  <label for="meter_description" class="form-label mb-1">Meter Description</label>
                    <input type="text" class="form-control" id="meter_description" name="meter_description" old="meter_description" value="{{ $meter_type->meter_description }}" required>
                </div>
              </div>

              
            </div>
            <div class="row">
              <div class="col-lg-12 text-end pt-2 gap-2">
                <a type="button" class="btn btn-sm btn-warning" href="{{ route('meter-type.index') }}"><i class="fa fa-times"></i> Cancel</a>
                <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-floppy-disk"></i> Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection