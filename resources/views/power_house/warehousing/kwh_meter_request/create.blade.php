@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Create kWh Meter Request</span>
              </div>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('kwh-meter-request.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="user_id" class="form-label mb-1">Requested By:</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                      <option value="">Select User</option>
                      @foreach($users as $id => $user)
                          <option value="{{ $id }}">{{ $user }}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="meter_code_id" class="form-label mb-1">Meter Type</label>
                    <select class="form-select" id="meter_code_id" name="meter_code_id" required>
                      <option value="">Select Meter Type</option>
                      @foreach($meters_types as $meter_type)
                          <option value="{{ $meter_type->id }}">{{ $meter_type->meter_description }}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="quantity" class="form-label mb-1">Quantity</label>
                    <input type="number" class="form-control" id="quantity" max="10" name="quantity" old="quantity" required>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="approved_by" class="form-label mb-1">Approved By:</label>
                    <select class="form-select" id="approved_by" name="approved_by" required>
                      <option value="">Select User</option>
                      @foreach($users as $id => $user)
                          <option value="{{ $id }}">{{ $user }}</option>
                      @endforeach
                    </select>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="purpose" class="form-label mb-1">Purpose</label>
                    {{-- <input type="text" class="form-control" id="purpose" name="purpose" old="purpose" required> --}}
                    <textarea name="purpose" id="purpose" class="form-control" required></textarea>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-lg-12 text-end pt-2 gap-2">
                <a type="button" class="btn btn-sm btn-warning" href="{{ route('kwh-meter-request.index') }}"><i class="fa fa-times"></i> Cancel</a>
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